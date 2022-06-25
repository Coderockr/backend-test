import { HttpException, HttpStatus, Injectable } from '@nestjs/common';
import { InjectModel } from '@nestjs/sequelize';
import Taxes from 'src/enums/taxes';
import DatesHelpers from 'src/helpers/dates-helpers';

import { CreateInvestmentDto } from './dto/create-investment.dto';
import { ListParamsDto } from './dto/list-params.dto';
import { WithdrawalInvestmentDto } from './dto/withdrawal-investment.dto';
import { Investment } from './entities/investment.entity';

@Injectable()
export class InvestmentService {
  constructor( @InjectModel(Investment)
  private investmentModel: typeof Investment,
  private dateHelper: DatesHelpers) {}

  create(createInvestmentDto: CreateInvestmentDto) {
    let midnightDate = new Date();
    midnightDate.setHours(24,0,0,0)
   
    if(this.dateHelper.verifyFutureDate(createInvestmentDto.date, midnightDate)) {
      throw new HttpException('Unable to register at a future date', HttpStatus.BAD_REQUEST);
    }

    return this.investmentModel.create({
      owner: createInvestmentDto.owner.toLowerCase(),
      amount: createInvestmentDto.amount,
      date: createInvestmentDto.date,
    });
  }

  findAll() {
    return this.investmentModel.findAll();
  }

  async view(id: string) {
    const investment = await this.investmentModel.findByPk(id);

    if(!investment) {
      throw new HttpException(`No investment with id ${id}`, HttpStatus.NOT_FOUND);
    }

    const {total} = this.calculateCompoundsGains(investment.amount, investment.date);

    return `Balance: ${total.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'})}`;
  }

  async withdrawal(withdrawalInvestmentDto: WithdrawalInvestmentDto) {
    console.log(withdrawalInvestmentDto);
    let midnightDate = new Date();
    midnightDate.setHours(24,0,0,0)

    const investment = await this.investmentModel.findByPk(withdrawalInvestmentDto.id);

    if(!investment) {
      throw new HttpException(`No investment with id ${withdrawalInvestmentDto.id}`, HttpStatus.NOT_FOUND);
    }

    if(this.dateHelper.verifyFutureDate(withdrawalInvestmentDto.date, investment.date)) {
      throw new HttpException('Unable to withdraw before the investment date', HttpStatus.BAD_REQUEST);
    }

    if(this.dateHelper.verifyFutureDate(withdrawalInvestmentDto.date, midnightDate)) {
      throw new HttpException('Unable to withdraw at a future date', HttpStatus.BAD_REQUEST);
    }

    let {gains, monthsInvested} = this.calculateCompoundsGains(investment.amount, investment.date);

    if(monthsInvested > 1 && monthsInvested < 12) { 
      gains = gains - gains*Taxes.LESSTHANONEYEAR;
    }else if(monthsInvested >=12 && monthsInvested < 24) {
      gains = gains - gains*Taxes.BETWEENONEANDTWOYEARS;
    }else {
      gains = gains - gains*Taxes.MORETHANTWOYEARS;
    }

    const total = investment.amount + gains;

    investment.update({amount: 0});

    return `Withdrawal sucessefully ${total}`;
  }

  async list(params: ListParamsDto) {
    const insvestments = await this.investmentModel.findAll({
      where:{owner: params.owner.toLowerCase()},
      offset: params?.offset ? params.offset : 0,
      limit: params?.limit ? params.limit : 10,
      order: [['id', 'DESC']],
    });

    if(insvestments.length < 1) {
      throw new HttpException(`No investment with owner: ${params.owner}`, HttpStatus.NOT_FOUND);
    }

    return insvestments;
  }

  calculateCompoundsGains(amount: number, date: string){
    const compoundTax = 0.52;

    const monthsInvested = this.dateHelper.getMonthDifference(date, new Date());

    const total =  amount * Math.pow((1+ compoundTax),monthsInvested);
    const gains = total - amount;

    return {total, gains, monthsInvested};
  }
}

import { Injectable, HttpException, HttpStatus } from '@nestjs/common';
import { getManager, Repository, SelectQueryBuilder } from 'typeorm';
import { InjectRepository } from '@nestjs/typeorm';
import { PaginationDto } from '../common/interfaces/pagination.dto';
import { Investment } from './entities/investment.entity';
import { Investor } from '../investors/entities/investor.entity';
import * as moment from 'moment';
import * as _ from 'lodash';
import { CreateInvestmentDto } from './dto/create-investment.dto';
import { InvestmentResultDto } from './dto/find-investment-result.dto';
import { MakeWithdrawalDto } from './dto/make-withdrawal.dto';
import { WithdrawalResultDto } from './dto/withdrawal-result.dto';
import { PaginatedInvestmentsResultDto } from './dto/paginated-investments-result.dto';

@Injectable()
export class InvestmentsService {
  constructor(
    @InjectRepository(Investment)
    private readonly investmentsRepository: Repository<Investment>,
    @InjectRepository(Investor)
    private readonly investorRepository: Repository<Investor>,
  ) {}

  private generateEntityManager(): SelectQueryBuilder<Investment> {
    return getManager().createQueryBuilder(Investment, 'investments');
  }

  async create(createInvestmentDto: CreateInvestmentDto): Promise<Investment> {
    const { investor_name, amount, reference_date } = createInvestmentDto;

    if (moment(createInvestmentDto.reference_date).isAfter(moment())) {
      throw new HttpException(
        'The investment date cannot be the current date or a date in the past',
        HttpStatus.BAD_REQUEST,
      );
    }

    const investor = await this.findOrCreateInvestor(investor_name);

    const investment = this.investmentsRepository.create({
      reference_date: reference_date,
      amount: amount,
      investor_id: investor.id,
    });

    return await investment.save();
  }

  async findOrCreateInvestor(name: string) {
    const investor = await this.investorRepository.findOne({ name: name });

    if (_.isUndefined(investor)) {
      return await this.investorRepository.create({ name: name }).save();
    }

    return investor;
  }

  /**
   * Retorna uma lista paginada de todos os investimentos de um investidor
   * @param paginationDto
   * @param {string} investor
   * @returns
   */
  async findByInvestor(
    paginationDto: PaginationDto,
    investor: string,
  ): Promise<PaginatedInvestmentsResultDto> {
    const skippedItems = (paginationDto.page - 1) * paginationDto.limit;

    const totalCount = await this.investmentsRepository.count();
    const investments = await this.generateEntityManager()
      .leftJoin('investments.investor', 'investor')
      .where('investor.name = :investor', { investor: investor })
      .orderBy('investments.created_at', 'DESC')
      .offset(skippedItems)
      .limit(paginationDto.limit)
      .getMany();

    return {
      totalCount,
      page: paginationDto.page,
      limit: paginationDto.limit,
      data: investments,
    };
  }

  /**
   * Calcula os ganhos sobre o valor de investimento inicial de acordo com os meses da carteira
   * @param investment
   * @returns
   */
  calculateInvestmentAmountWithGains(investment: Investment) {
    const diffInMonths = moment().diff(
      investment.reference_date,
      'months',
      false,
    );

    let amount = investment.amount;
    _.times(diffInMonths, () => {
      const withGained = (amount * 5.2) / 1000;
      amount = Number(amount) + Number(withGained.toFixed(2));
    });

    return Number(amount.toFixed(2));
  }

  async findInvestmentGains(id: number): Promise<InvestmentResultDto> {
    const investment = await this.investmentsRepository.findOneOrFail({
      id: id,
    });

    const calculatedGained =
      this.calculateInvestmentAmountWithGains(investment);

    return {
      initial_amount: investment.amount.toString(),
      expected_balance: calculatedGained.toFixed(2).toString(),
    };
  }

  /**
   * Calcula as taxas sobre os ganhos de acordo com anos anos da carteira
   * @param {Date} reference_date
   * @param {Date} witdrawal_date
   * @param {number} amount
   */
  calculateInvestmentTaxes(
    reference_date: Date,
    witdrawal_date: Date,
    amount: number,
  ) {
    const diffInYears = moment(witdrawal_date).diff(
      reference_date,
      'years',
      false,
    );

    if (_.lt(diffInYears, 1)) {
      return amount * 0.225;
    } else if (_.range(1, 2, diffInYears)) {
      return amount * 0.185;
    }

    return amount * 0.15;
  }

  async makeWithdrawal(
    makeWithdrawalDto: MakeWithdrawalDto,
  ): Promise<WithdrawalResultDto> {
    const { investment_id, date, amount } = makeWithdrawalDto;
    const investment = await this.investmentsRepository.findOneOrFail(
      investment_id,
    );

    if (moment(date).isSameOrBefore(moment(investment.reference_date))) {
      throw new HttpException(
        'The withdrawal date must be greater than the wallet reference date',
        HttpStatus.BAD_REQUEST,
      );
    }

    const initialInvestmentWithGains =
      this.calculateInvestmentAmountWithGains(investment);
    const amountOfGains = initialInvestmentWithGains - investment.amount;

    if (!_.isEqual(Number(amount), Number(initialInvestmentWithGains))) {
      throw new HttpException(
        'The withdrawal amount must be the total amount available in the wallet',
        HttpStatus.BAD_REQUEST,
      );
    }

    const earningsWithFees = this.calculateInvestmentTaxes(
      investment.reference_date,
      date,
      amountOfGains,
    );

    const availableAmountForWithdrawal =
      Number(initialInvestmentWithGains) - Number(earningsWithFees);

    return {
      date: date,
      withdrawal_amount: availableAmountForWithdrawal.toFixed(2),
    };
  }
}

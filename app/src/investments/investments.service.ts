import { PutInvestmentRequestDto } from './dto/req/put-investment-request.dto';
import { Investiment } from '@prisma/client';
import { InvestmentEntity } from './entities/investment.entity';
import { InvestimentsRepository } from './repositories/investiments.repository';
import { Injectable, Logger, BadRequestException } from '@nestjs/common';
import { UpdateInvestmentRequestDto } from './dto/req/update-investment-request.dto';
import { Cron, CronExpression } from '@nestjs/schedule';
import { NotFoundException } from '@nestjs/common/exceptions';
import { formatDate } from 'src/shared/format-date';
import { calculateBalance } from 'src/shared/calculate-expected-balance';
import { calculateTaxByDate } from 'src/shared/calculate-tax-by-date';
import { CreateInvestmentRequestDto } from './dto/req/create-investment-request.dto';
import { OwnersService } from 'src/owners/owners.service';
import { calculateDayDiff } from 'src/shared/calculate-day-diff';
import { MailService } from 'src/mail/mail.service';

@Injectable()
export class InvestmentsService {
  private readonly logger = new Logger(InvestmentsService.name);

  constructor(
    private readonly investimentsRepository: InvestimentsRepository,
    private readonly ownerService: OwnersService,
    private readonly mailService: MailService,
  ) {}

  async create(
    createInvestmentRequesteDto: CreateInvestmentRequestDto,
  ): Promise<Investiment> {
    const { amount, creation_date, owner_id } = createInvestmentRequesteDto;

    const owner = await this.ownerService.findOne(owner_id);

    if (!owner) {
      throw new NotFoundException('Owner not found');
    }

    const expectedBalance = calculateBalance(+amount);

    return this.investimentsRepository.create({
      amount,
      expected_balance: expectedBalance,
      creation_date,
      owner_id,
    });
  }

  findAll() {
    return this.investimentsRepository.findAll();
  }

  async findOne(id: number) {
    const investiment = await this.investimentsRepository.findOne(id);

    if (!investiment) {
      throw new NotFoundException('Investment not found');
    }

    return investiment;
  }

  update(id: number, updateInvestmentRequestDto: UpdateInvestmentRequestDto) {
    const investiment = this.investimentsRepository.findOne(id);

    if (!investiment) {
      throw new NotFoundException();
    }

    return this.investimentsRepository.update(id, updateInvestmentRequestDto);
  }

  async withdrawalInvestment(
    id: number,
    { withdrawal_date }: PutInvestmentRequestDto,
  ) {
    const investment: Investiment = await this.investimentsRepository.findOne(
      id,
    );

    if (!investment) {
      throw new NotFoundException('Investment not found');
    }

    const withdrawalDate = new Date(withdrawal_date);
    const creationDate = new Date(investment.creation_date);

    const isWithdrawlOldThanCreationDate = calculateDayDiff(
      creationDate,
      withdrawalDate,
    );

    if (isWithdrawlOldThanCreationDate < 0)
      throw new BadRequestException(
        "Invalid date - date can't be old than creation_date",
      );

    const amountGained = +investment.amount - +investment.initial_amount;

    const amountWithTax: number = calculateTaxByDate(
      investment.amount,
      amountGained,
      investment.creation_date,
      withdrawalDate,
    );

    return this.investimentsRepository.withdrawalInvestment(id, {
      amount: amountWithTax,
      expected_balance: amountWithTax,
    });
  }

  async calculateDailyGain() {
    const investments = await this.investimentsRepository.findAll();
    const today = new Date();

    investments.forEach(async (investment: InvestmentEntity) => {
      const { creation_date } = investment;
      const investmentInitialDate = new Date(creation_date);

      if (investment.active) {
        if (formatDate(today) != formatDate(investmentInitialDate)) {
          if (investmentInitialDate.getDate() == today.getDate()) {
            //this point does not validate days when fev - 29 - 31 - of any month...
            //example: january 29 - when date are fev - 28, the month change to march

            const newAmount = investment.expected_balance;
            const newExpectedBalance = calculateBalance(+newAmount);

            const data: UpdateInvestmentRequestDto = {
              expected_balance: newExpectedBalance,
              amount: newAmount,
            };

            await this.investimentsRepository.update(investment.id, data);
            //here you can sendEmailTo -> investment
            this.mailService.sendMailTo(
              investment.owner.email,
              investment.owner.name,
              `Your investment is paying off! - Gain: ${newAmount}`,
            );
          }
        }
      }
    });
  }

  @Cron(CronExpression.EVERY_DAY_AT_5AM)
  calculateGainSchedule() {
    this.logger.debug('Called Calculate Gain Schedule Method');
    this.calculateDailyGain();
  }
}

import { Investiment } from '@prisma/client';
import { InvestmentEntity } from './entities/investment.entity';
import { OwnerRepository } from './../owners/repositories/owners.repository';
import { InvestimentRepository } from './repositories/investiments.repository';
import { Injectable, Logger } from '@nestjs/common';
import { UpdateInvestmentRequestDto } from './dto/req/update-investment-request.dto';
import { CreateInvestmentResponseDto } from './dto/res/create-investment-response.dto';
import { Cron } from '@nestjs/schedule';
import { NotFoundException } from '@nestjs/common/exceptions';
import { formatDate } from 'src/shared/format-date';
import { calculateBalance } from 'src/shared/calculateExpectedBalance';
import { calculateTaxByDate } from 'src/shared/calculateTaxByDate';

@Injectable()
export class InvestmentsService {
  private readonly logger = new Logger(InvestmentsService.name);

  constructor(
    private readonly investimentRepository: InvestimentRepository,
    private readonly ownerRepository: OwnerRepository,
  ) {}

  async create(createInvestmentResponseDto: CreateInvestmentResponseDto) {
    const { amount, creation_date, owner_id } = createInvestmentResponseDto;

    const owner = await this.ownerRepository.findOne(owner_id);

    if (!owner) {
      throw new NotFoundException('Owner not found');
    }

    const expectedBalance = calculateBalance(amount);

    return this.investimentRepository.create({
      amount,
      expected_balance: expectedBalance,
      creation_date,
      owner_id,
    });
  }

  findAll() {
    return this.investimentRepository.findAll();
  }

  findOne(id: number) {
    return this.investimentRepository.findOne(id);
  }

  update(id: number, updateInvestmentRequestDto: UpdateInvestmentRequestDto) {
    const investiment = this.investimentRepository.findOne(id);

    if (!investiment) {
      throw new NotFoundException();
    }

    return this.investimentRepository.update(id, updateInvestmentRequestDto);
  }

  async withdrawalInvestment(id: number) {
    const investment: Investiment = await this.investimentRepository.findOne(
      id,
    );

    if (!investment) {
      throw new NotFoundException('Investment not found');
    }

    const amountWithTax: number = calculateTaxByDate(
      investment.amount,
      investment.creation_date,
    );

    return this.investimentRepository.withdrawalInvestment(id, {
      amount: amountWithTax,
    });
  }

  async calculateDailyGain() {
    const investments = await this.investimentRepository.findAll();
    const today = new Date();

    await investments.forEach(async (investment: InvestmentEntity) => {
      const { creation_date } = investment;
      const investmentInitialDate = new Date(creation_date);

      if (formatDate(today) != formatDate(investmentInitialDate)) {
        if (investmentInitialDate.getDate() == today.getDate()) {
          const newAmount = calculateBalance(investment.amount);
          const newExpectedBalance = calculateBalance(newAmount);

          const data: UpdateInvestmentRequestDto = {
            expected_balance: newExpectedBalance,
            amount: newAmount,
          };

          await this.investimentRepository.update(investment.id, data);
        }
      }
    });
  }

  @Cron('45 * * * * *')
  calculateGainSchedule() {
    this.logger.debug('Called Calculate Gain Method');
    this.calculateDailyGain();
  }
}

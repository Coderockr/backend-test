import { InvestmentEntity } from './entities/investment.entity';
import { OwnerRepository } from './../owners/repositories/owners.repository';
import { Investiment } from '@prisma/client';
import { InvestimentRepository } from './repositories/investiments.repository';
import { Injectable, Logger } from '@nestjs/common';
import { UpdateInvestmentRequestDto } from './dto/req/update-investment-request.dto';
import { CreateInvestmentResponseDto } from './dto/res/create-investment-response.dto';
import { Cron } from '@nestjs/schedule';
import { GainTax } from 'src/shared/gain';
import { NotFoundException } from '@nestjs/common/exceptions';
import { formatDate } from 'src/shared/format-date';

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

    const expected_balance = amount + amount * GainTax.VALUE;

    return this.investimentRepository.create({
      amount,
      expected_balance,
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

  withdrawalInvestment(id: number) {
    return `This action removes a #${id} investment`;
  }

  async calculateDailyGain() {
    const investments = await this.investimentRepository.findAll();
    const today = new Date();
    const investmentsToUpdate = [];

    await investments.forEach((investment: InvestmentEntity) => {
      const { creation_date } = investment;
      const investmentInitialDate = new Date(creation_date);

      if (formatDate(today) != formatDate(investmentInitialDate)) {
        if (investmentInitialDate.getDate() == today.getDate()) {
          investmentsToUpdate.push(investment);
        }
      }
    });

    return investmentsToUpdate;
  }

  @Cron('45 * * * * *')
  calculateGainSchedule() {
    this.logger.debug('Called Calculate Gain Method');
    this.calculateDailyGain();
  }
}

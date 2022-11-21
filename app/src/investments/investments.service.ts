import { Investiment } from '@prisma/client';
import { InvestimentRepository } from './repositories/investiments.repository';
import { Injectable, Logger } from '@nestjs/common';
import { UpdateInvestmentRequestDto } from './dto/req/update-investment-request.dto';
import { CreateInvestmentResponseDto } from './dto/res/create-investment-response.dto';
import { Cron } from '@nestjs/schedule';
import { GainTax } from 'src/shared/gain';
import { NotFoundException } from '@nestjs/common/exceptions';

@Injectable()
export class InvestmentsService {
  private readonly logger = new Logger(InvestmentsService.name);

  constructor(private readonly investimentRepository: InvestimentRepository) {}

  create(createInvestmentResponseDto: CreateInvestmentResponseDto) {
    const { amount, creation_date, owner_id } = createInvestmentResponseDto;

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

  async calculateDalyGain() {
    const investments = await this.investimentRepository.findAll();
    const today = new Date();

    investments.forEach(investment => {
      console.log(investment);
    });
  }

  @Cron('30 * * * * *')
  calculateGain() {
    this.logger.debug('Called Calculate Gain Method');
    this.calculateGain();
  }
}

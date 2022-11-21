import { InvestimentRepository } from './repositories/investiments.repository';
import { Injectable } from '@nestjs/common';
import { CreateInvestmentDto } from './dto/create-investment.dto';
import { UpdateInvestmentDto } from './dto/update-investment.dto';

@Injectable()
export class InvestmentsService {
  constructor(private readonly investimentRepository: InvestimentRepository) {}

  create(createInvestmentDto: CreateInvestmentDto) {
    return this.investimentRepository.create(createInvestmentDto);
  }

  findAll() {
    return `This action returns all investments`;
  }

  findOne(id: number) {
    return `This action returns a #${id} investment`;
  }

  update(id: number, updateInvestmentDto: UpdateInvestmentDto) {
    return `This action updates a #${id} investment`;
  }

  remove(id: number) {
    return `This action removes a #${id} investment`;
  }
}

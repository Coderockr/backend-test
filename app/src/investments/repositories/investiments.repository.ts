import { CreateInvestmentDto } from './../dto/create-investment.dto';
import { Injectable } from '@nestjs/common';
import { Investiment } from '@prisma/client';
import { PrismaService } from 'src/prisma/prisma.service';

@Injectable()
export class InvestimentRepository {
  constructor(private readonly prisma: PrismaService) {}
  async create({
    amount,
    creation_date,
    owner_id,
  }: CreateInvestmentDto): Promise<Investiment> {
    return null;
    return this.prisma.investiment.create({
      data: {
        amount,
        initial_amount: amount,
        creation_date,
        owner: { connect: { id: owner_id } },
      },
    });
  }
}

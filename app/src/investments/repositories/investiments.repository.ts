import { UpdateInvestmentRequestDto } from './../dto/update-investment-request.dto';
import { CreateInvestmentRequestDto } from '../dto/create-investment-request.dto';
import { BadRequestException, Injectable } from '@nestjs/common';
import { Investiment } from '@prisma/client';
import { PrismaService } from '../../prisma/prisma.service';

@Injectable()
export class InvestimentsRepository {
  constructor(private readonly prisma: PrismaService) {}
  async create({
    amount,
    expected_balance,
    creation_date,
    owner_id,
  }: CreateInvestmentRequestDto): Promise<Investiment> {
    try {
      return this.prisma.investiment.create({
        data: {
          amount,
          initial_amount: amount,
          expected_balance,
          creation_date,
          owner: { connect: { id: owner_id } },
        },
      });
    } catch (e) {
      throw new BadRequestException(e);
    }
  }

  async update(
    id: number,
    { amount, expected_balance }: UpdateInvestmentRequestDto,
  ) {
    try {
      return this.prisma.investiment.update({
        where: { id },
        data: { amount, expected_balance },
      });
    } catch (e) {
      throw new BadRequestException(e);
    }
  }

  async findAll(): Promise<Investiment[] | []> {
    try {
      return this.prisma.investiment.findMany({
        select: {
          initial_amount: true,
          id: true,
          expected_balance: true,
          amount: true,
          active: true,
          creation_date: true,
          owner_id: true,
          owner: { select: { name: true, email: true } },
        },
      });
    } catch (e) {
      throw new BadRequestException(e);
    }
  }

  async findOne(id: number): Promise<Investiment | null> {
    try {
      return this.prisma.investiment.findFirst({
        where: { id },
        //, active: true
      });
    } catch (e) {
      throw new BadRequestException();
    }
  }

  async withdrawalInvestment(
    id: number,
    { amount, expected_balance }: UpdateInvestmentRequestDto,
  ) {
    return this.prisma.investiment.update({
      where: { id },
      data: { amount, expected_balance, active: false },
    });
  }
}

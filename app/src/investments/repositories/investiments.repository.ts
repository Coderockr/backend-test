import { UpdateInvestmentRequestDto } from './../dto/req/update-investment-request.dto';
import { CreateInvestmentRequestDto } from '../dto/req/create-investment-request.dto';
import { BadRequestException, Injectable } from '@nestjs/common';
import { Investiment } from '@prisma/client';
import { PrismaService } from 'src/prisma/prisma.service';

@Injectable()
export class InvestimentRepository {
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
    updateInvestmentRequestDto: UpdateInvestmentRequestDto,
  ) {
    const { amount, creation_date, expected_balance, owner_id } =
      updateInvestmentRequestDto;
    try {
      this.prisma.investiment.update({
        where: { id },
        data: { amount, creation_date, expected_balance, owner_id },
      });
    } catch (e) {
      throw new BadRequestException(e);
    }
  }

  async findAll(): Promise<Investiment[] | []> {
    try {
      return this.prisma.investiment.findMany({});
    } catch (e) {
      throw new BadRequestException(e);
    }
  }

  async findOne(id: number): Promise<Investiment | null> {
    return this.prisma.investiment.findFirstOrThrow({
      where: { id, active: true },
    });
  }

  async withdrawal(id: number) {
    return this.prisma.investiment.update({
      where: { id },
      data: { active: false },
    });
  }
}

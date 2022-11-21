import { CreateOwnerDto } from './../dto/create-owner.dto';
import { Injectable } from '@nestjs/common';
import { Owner } from '@prisma/client';
import { PrismaService } from 'src/prisma/prisma.service';

@Injectable()
export class OwnerRepository {
  constructor(private readonly prisma: PrismaService) {}
  async create({ name, email }: CreateOwnerDto): Promise<Owner> {
    return this.prisma.owner.create({
      data: { name, email },
    });
  }

  async findOne(id: number, skip = 0, take = 10) {
    return this.prisma.owner.findFirstOrThrow({
      where: { id },
      include: {
        Investiment: {
          select: { creation_date: true, amount: true },
          skip,
          take,
        },
      },
    });
  }

  async findAll(skip: number, take: number) {
    return this.prisma.owner.findMany({
      include: {
        Investiment: {
          select: { creation_date: true, amount: true },
          skip,
          take,
        },
      },
    });
  }
}

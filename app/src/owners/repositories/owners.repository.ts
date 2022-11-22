import { UpdateOwnerDto } from './../dto/update-owner.dto';
import { CreateOwnerDto } from './../dto/create-owner.dto';
import { Injectable, BadRequestException } from '@nestjs/common';
import { Owner } from '@prisma/client';
import { PrismaService } from '../../prisma/prisma.service';

@Injectable()
export class OwnersRepository {
  constructor(private readonly prisma: PrismaService) {}
  async create({ name, email }: CreateOwnerDto): Promise<Owner> {
    try {
      return this.prisma.owner.create({
        data: { name, email },
      });
    } catch (e) {
      throw new BadRequestException(e);
    }
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
    try {
      return this.prisma.owner.findMany({
        include: {
          Investiment: {
            select: { creation_date: true, amount: true },
            skip,
            take,
          },
        },
      });
    } catch (e) {
      throw new BadRequestException(e);
    }
  }

  async update(id: number, { name, email }: UpdateOwnerDto) {
    try {
      this.prisma.owner.update({ where: { id }, data: { name, email } });
    } catch (e) {
      throw new BadRequestException(e);
    }
  }

  async remove(id) {
    try {
      return this.prisma.owner.delete({ where: id });
    } catch (e) {
      throw new BadRequestException(e);
    }
  }
}

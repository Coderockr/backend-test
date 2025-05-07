import { ICreateInvestorDTO } from "@modules/investors/dtos/ICreateInvestorDTO";
import { Investor } from "@modules/investors/entities/Investor";
import { IInvestorRepository } from "@modules/investors/repositories/IInvestorRepository";
import { prisma } from "../../../../../database/prismaClient";


class InvestorRepository implements IInvestorRepository {


  async create({ name, email, password }: ICreateInvestorDTO): Promise<Investor> {
    const investor = await prisma.investor.create({
      data: {
        name,
        email,
        password
      },
    });

    return investor;
  }

  async findByEmail(email: string): Promise<Investor> {
    const investor = await prisma.investor.findFirst({
      where: {
        email: {
          equals: email,
          mode: "insensitive",
        },
      },
    });

    return investor;
  }

  async findById(id: string): Promise<Investor> {
    const investor = await prisma.investor.findFirst({
      where: {
        id: {
          equals: id,
          mode: "insensitive",
        },
      },
    });

    return investor;
  }
}

export { InvestorRepository }
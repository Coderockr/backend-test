import { prisma } from "src/database/prismaClient";
import { ICreateInvestorDTO } from "src/modules/investors/dtos/ICreateInvestorDTO";
import { Investor } from "src/modules/investors/entities/Investor";
import { IInvestorRepository } from "src/modules/investors/repositories/IInvestorRepository";


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
}

export { InvestorRepository }
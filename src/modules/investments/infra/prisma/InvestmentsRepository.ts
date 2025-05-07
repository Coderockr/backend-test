import { prisma } from "@database/prismaClient";
import { ICreateInvestmentDTO } from "@modules/investments/dtos/ICreateInvestmentDTO";
import { Investment } from "@modules/investments/entities/Investment";
import { IInvestmentsRepository } from "@modules/investments/repositories/IInvestmentsRepository";


class InvestmentsRepository implements IInvestmentsRepository {


  findByIdInvestor(id: string): Promise<Investment> {
    throw new Error("Method not implemented.");
  }

  async create({ id_investor, created_at, capital }: ICreateInvestmentDTO): Promise<Investment> {
    const investment = await prisma.investments.create({
      data: {
        id_investor,
        created_at,
        capital
      },
    });

    return investment;
  }

  async findById(id: string): Promise<Investment> {
    const investment = await prisma.investments.findFirst({
      where: {
        id,
      },
    });

    return investment;
  }

  async toWithdrawn(id: string, date_withdraw: Date, withdraw_value: number, rate: number): Promise<Investment> {
    const result = await prisma.investments.update({
      where: {
        id: id,
      },
      data: {
        withdraw_at: date_withdraw,
        withdraw_value,
        withdraw_rate: rate
      },
    });

    return result;
  }

  async findManyByInvestor(id_investor: string, page: number): Promise<Investment[]> {
    const investments = await prisma.investments.findMany({
      skip: (page) ? page * 2 : 0,
      take: 2,
      where: {
        id_investor
      },
    });

    return investments;
  }
}

export { InvestmentsRepository }
import { prisma } from "@database/prismaClient";
import { ICreateInvestmentDTO } from "@modules/investments/dtos/ICreateInvestmentDTO";
import { Investment } from "@modules/investments/entities/Investment";
import { IInvestmentsRepository } from "@modules/investments/repositories/IInvestmentsRepository";


class InvestmentsRepository implements IInvestmentsRepository {
  findByIdInvestor(id: string): Promise<Investment> {
    throw new Error("Method not implemented.");
  }


  async create({ id_investor, created_at, capital }: ICreateInvestmentDTO): Promise<Investment> {
    const investor = await prisma.investments.create({
      data: {
        id_investor,
        created_at,
        capital
      },
    });

    return investor;
  }


}

export { InvestmentsRepository }
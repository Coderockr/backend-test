import { inject, injectable } from 'tsyringe';

import { DayjsDateProvider } from "../../../../shared/container/providers/DateProvider/implementations/DayjsDateProvider";
import { prisma } from "../../../../database/prismaClient";

interface ICreateInvestment {
  id_investor: string;
  created_at: Date;
  capital: number;
}

@injectable()
export class CreateInvestmentUseCase {

  constructor(
    @inject('DayjsDateProvider')
    private dateProvider: DayjsDateProvider,
  ) { }

  async execute({ id_investor, created_at, capital }: ICreateInvestment) {

    if (capital <= 0) {
      throw new Error("Amount not allowed");
    }

    const dateNow = this.dateProvider.dateNow();

    let diffDate = this.dateProvider.compareInDays(
      created_at,
      dateNow,
    );

    if (diffDate < 0) {
      throw new Error("Date invalid");
    }

    const investment = await prisma.investments.create({
      data: {
        id_investor,
        created_at,
        capital
      },
    });

    return investment;
  }
}

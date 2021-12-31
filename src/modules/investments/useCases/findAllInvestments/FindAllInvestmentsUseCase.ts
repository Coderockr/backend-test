import { inject, injectable } from 'tsyringe';

import { DayjsDateProvider } from "../../../../shared/container/providers/DateProvider/implementations/DayjsDateProvider";
import { prisma } from "../../../../database/prismaClient";

interface IFindInvestment {
  id_investor: string;
  page: string;
}

@injectable()
export class FindAllInvestmentsUseCase {

  constructor(
    @inject('DayjsDateProvider')
    private dateProvider: DayjsDateProvider,
  ) { }

  async execute({ id_investor, page }: IFindInvestment) {

    const investments = await prisma.investments.findMany({
      skip: (page) ? parseInt(page) : 0,
      take: 2,
      where: {
        id_investor
      },
    });

    let newInvestments = [];

    if (investments.length > 0) {
      investments.map((investment, value) => {

        const dateNow = this.dateProvider.dateNow();

        let numberMonths = this.dateProvider.compareInMonth(
          investment.created_at,
          dateNow,
        );

        investment['meses'] = numberMonths;


        let amount = investment.capital * ((1 + 0.0052) ** numberMonths);

        investment['montante'] = amount.toFixed(2);


        newInvestments.push(investment);

      })
    }

    return newInvestments;
  }
}

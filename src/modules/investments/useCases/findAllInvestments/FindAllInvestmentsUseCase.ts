import { inject, injectable } from 'tsyringe';

import { CalculateGainProvider } from '@shared/container/providers/CalculateGainProvider/implementations/CalculateGainProvider';
import { prisma } from '@database/prismaClient';
import { DayjsDateProvider } from '@shared/container/providers/DateProvider/implementations/DayjsDateProvider';
import { IInvestmentsRepository } from '@modules/investments/repositories/IInvestmentsRepository';

interface IFindInvestment {
  id_investor: string;
  page: number;
}

@injectable()
export class FindAllInvestmentsUseCase {

  constructor(
    @inject('DayjsDateProvider')
    private dateProvider: DayjsDateProvider,
    @inject('CalculateGainProvider')
    private calculateGainProvider: CalculateGainProvider,
    @inject('InvestmentsRepository')
    private investmentsRepository: IInvestmentsRepository
  ) { }

  async execute({ id_investor, page }: IFindInvestment) {

    const investments = await this.investmentsRepository.findManyByInvestor(id_investor, page);

    let newInvestments = [];

    if (investments.length > 0) {
      investments.map((investment, value) => {

        const dateNow = this.dateProvider.dateNow();

        let numberMonths = this.dateProvider.compareInMonth(
          investment.created_at,
          dateNow,
        );

        const amount = this.calculateGainProvider.calculateAmount(investment.capital, numberMonths);

        const gain = amount - investment.capital;

        const rate = this.calculateGainProvider.calculateRate(gain, numberMonths);

        const withdraw_value = gain - rate;

        Object.assign(investment, {
          capital: investment.capital.toFixed(2),
          period_month: numberMonths,
          income: withdraw_value.toFixed(2),
          tax: rate.toFixed(2),
          total_brute: amount.toFixed(2),
          total_withdrawn: (investment.capital + withdraw_value).toFixed(2)
        });

        newInvestments.push(investment);
      })
    }

    return newInvestments;
  }
}

import { inject, injectable } from 'tsyringe';

import { DayjsDateProvider } from "../../../../../shared/container/providers/DateProvider/implementations/DayjsDateProvider";

import { prisma } from "../../../../../database/prismaClient";
import { CalculateGainProvider } from '../../../../../shared/container/providers/CalculateGainProvider/implementations/CalculateGainProvider';
import { InvestmentsRepository } from '@modules/investments/infra/prisma/InvestmentsRepository';
import { IInvestmentsRepository } from '@modules/investments/repositories/IInvestmentsRepository';

interface IWithdrawn {
  id_investor: string;
  id: string;
  withdraw_at: Date;
}

@injectable()
export class WithdrawnInvestmentUseCase {

  constructor(
    @inject('DayjsDateProvider')
    private dateProvider: DayjsDateProvider,
    @inject('CalculateGainProvider')
    private calculateGainProvider: CalculateGainProvider,
    @inject('InvestmentsRepository')
    private investmentsRepository: IInvestmentsRepository,
  ) { }

  async execute({ id, withdraw_at }: IWithdrawn) {

    const investment = await this.investmentsRepository.findById(id)

    if (!investment) {
      throw new Error("Investment not found");
    }

    const date_withdraw = (withdraw_at) ? withdraw_at : this.dateProvider.dateNow();

    let numberMonths = this.dateProvider.compareInMonth(
      investment.created_at,
      date_withdraw,
    );

    const amount = this.calculateGainProvider.calculateAmount(investment.capital, numberMonths);

    const gain = amount - investment.capital;

    const rate = this.calculateGainProvider.calculateRate(gain, numberMonths);

    const withdraw_value = gain - rate + investment.capital;

    const result = await this.investmentsRepository.toWithdrawn(
      id,
      date_withdraw,
      parseFloat(withdraw_value.toFixed(2)),
      parseFloat(rate.toFixed(2)),
    );

    return result;
  }
}

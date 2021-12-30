import { inject, injectable } from 'tsyringe';

import { DayjsDateProvider } from "../../../../../shared/container/providers/DateProvider/implementations/DayjsDateProvider";

import { prisma } from "../../../../../database/prismaClient";
import { CalculateGainProvider } from '../../../../../shared/container/providers/CalculateGainProvider/implementations/CalculateGainProvider';

interface IUpdateWithdrawn {
  id_investor: string;
  id: string;
  withdraw_at: Date;
}

@injectable()
export class UpdateWithdrawnUseCase {

  constructor(
    @inject('DayjsDateProvider')
    private dateProvider: DayjsDateProvider,
    @inject('CalculateGainProvider')
    private calculateGainProvider: CalculateGainProvider,
  ) { }

  async execute({ id_investor, id, withdraw_at }: IUpdateWithdrawn) {

    const investment = await prisma.investments.findFirst({
      where: {
        id,
        id_investor
      },
    });

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

    const withdraw_value = gain - rate;

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
}

import { inject, injectable } from 'tsyringe';

import { DayjsDateProvider } from "../../../../shared/container/providers/DateProvider/implementations/DayjsDateProvider";
import { IInvestmentsRepository } from '@modules/investments/repositories/IInvestmentsRepository';
import { IInvestorRepository } from '@modules/investors/repositories/IInvestorRepository';

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
    @inject('InvestmentRepository')
    private investmentsRepository: IInvestmentsRepository,
    @inject('InvestorRepository')
    private investorRepository: IInvestorRepository
  ) { }

  async execute({ id_investor, created_at, capital }: ICreateInvestment) {

    const investorExist = await this.investorRepository.findById(id_investor);

    if (!investorExist) {
      throw new Error("Investor not exist");
    }

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

    const investment = await this.investmentsRepository.create({
      id_investor, created_at, capital
    });

    return investment;
  }
}

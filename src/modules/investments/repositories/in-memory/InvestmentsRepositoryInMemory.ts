import { ICreateInvestmentDTO } from "@modules/investments/dtos/ICreateInvestmentDTO";
import { Investment } from "@modules/investments/entities/Investment";
import { IInvestmentsRepository } from "../IInvestmentsRepository";

class InvestmentsRepositoryInMemory implements IInvestmentsRepository {

  investments: Investment[] = [];

  async create({ id_investor, created_at, capital }: ICreateInvestmentDTO): Promise<Investment> {
    const investment = new Investment();

    Object.assign(investment, {
      id_investor,
      created_at,
      capital
    });

    this.investments.push(investment);

    return investment;

  }

  async findByIdInvestor(id_investor: string): Promise<Investment> {
    return this.investments.find((investment) => investment.id_investor === id_investor);
  }

  async findById(id: string): Promise<Investment> {
    return this.investments.find((investment) => investment.id === id);
  }

  async toWithdrawn(id: string, date_withdraw: Date, withdraw_value: number, rate: number): Promise<Investment> {
    const investment = this.investments.find((investment) => investment.id === id);

    Object.assign(investment, {
      withdrawn_at: date_withdraw,
      withdraw_value,
      withdraw_rate: rate
    });

    return investment;

  }

  findManyByInvestor(id_investor: string, page: number): Promise<Investment[]> {
    throw new Error("Method not implemented.");
  }
}

export { InvestmentsRepositoryInMemory }
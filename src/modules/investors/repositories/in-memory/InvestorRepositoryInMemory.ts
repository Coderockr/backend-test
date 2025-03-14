import { ICreateInvestorDTO } from "../../dtos/ICreateInvestorDTO";
import { Investor } from "../../entities/Investor";
import { IInvestorRepository } from "../IInvestorRepository";


class InvestorRepositoryInMemory implements IInvestorRepository {


  investors: Investor[] = [];

  async create({ name, email, password }: ICreateInvestorDTO): Promise<Investor> {
    const investor = new Investor();

    Object.assign(investor, {
      name,
      email,
      password
    });

    this.investors.push(investor);

    return investor
  }

  async findByEmail(email: string): Promise<Investor> {
    return this.investors.find((investor) => investor.email === email);
  }

  async findById(id: string): Promise<Investor> {
    return this.investors.find((investor) => investor.id === id);
  }
}

export { InvestorRepositoryInMemory }
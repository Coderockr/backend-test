import { hash } from "bcrypt";
import { inject, injectable } from "tsyringe";
import { ICreateInvestorDTO } from "../../dtos/ICreateInvestorDTO";
import { IInvestorRepository } from "../../repositories/IInvestorRepository";


@injectable()
export class CreateInvestorUseCase {

  constructor(
    @inject('InvestorRepository')
    private investorRepository: IInvestorRepository
  ) { }

  async execute({ name, email, password }: ICreateInvestorDTO) {

    const investorExist = await this.investorRepository.findByEmail(email);

    if (investorExist) {
      throw new Error("Investor already exists");
    }

    const hashPassword = await hash(password, 10);

    const investor = this.investorRepository.create({
      name,
      email,
      password: hashPassword
    })

    return investor;
  }
}

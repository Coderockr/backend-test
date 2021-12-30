import { compare } from "bcrypt";
import { sign } from "jsonwebtoken";
import { injectable, inject } from "tsyringe";
import { IInvestorRepository } from "src/modules/investors/repositories/IInvestorRepository";

interface IAuthenticate {
  email: string;
  password: string;
}

@injectable()
export class AuthenticateUseCase {

  constructor(
    @inject('InvestorRepository')
    private authenticateRepository: IInvestorRepository
  ) { }
  async execute({ email, password }: IAuthenticate) {

    const investor = await this.authenticateRepository.findByEmail(email);

    if (!investor) {
      throw new Error("Email or password incorrect");
    }

    const passwordMatch = await compare(password, investor.password);

    if (!passwordMatch) {
      throw new Error("Email or password incorrect");
    }

    const token = sign({ email }, "019acc25a4e242bb55ad489832ada12d", {
      subject: investor.id,
      expiresIn: "1d",
    });

    const tokenReturn = {
      token
    }

    return tokenReturn;
  }
}

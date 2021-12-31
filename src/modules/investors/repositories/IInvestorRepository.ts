import { ICreateInvestorDTO } from "../dtos/ICreateInvestorDTO";
import { Investor } from "../entities/Investor";

interface IInvestorRepository {
  create(data: ICreateInvestorDTO): Promise<Investor>;
  findByEmail(email: string): Promise<Investor>;
  findById(id: string): Promise<Investor>;
}

export { IInvestorRepository }
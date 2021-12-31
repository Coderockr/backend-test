import { ICreateInvestmentDTO } from "../dtos/ICreateInvestmentDTO";
import { Investment } from "../entities/Investment";

interface IInvestmentsRepository {
  create(data: ICreateInvestmentDTO): Promise<Investment>;
  findByIdInvestor(id: string): Promise<Investment>;
  findById(id: string): Promise<Investment>;
  toWithdrawn(id: string, date_withdraw: Date, withdraw_value: number, rate: number): Promise<Investment>;
  findManyByInvestor(id_investor: string, page: number): Promise<Investment[]>;
}

export { IInvestmentsRepository }
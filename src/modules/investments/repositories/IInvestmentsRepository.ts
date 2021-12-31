import { ICreateInvestmentDTO } from "../dtos/ICreateInvestmentDTO";
import { Investment } from "../entities/Investment";

interface IInvestmentsRepository {
  create(data: ICreateInvestmentDTO): Promise<Investment>;
  findByIdInvestor(id: string): Promise<Investment>;
}

export { IInvestmentsRepository }
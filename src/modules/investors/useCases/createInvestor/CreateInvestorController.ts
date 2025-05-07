import { Request, Response } from "express";
import { container } from 'tsyringe';
import { CreateInvestorUseCase } from "./CreateInvestorUseCase";

export class CreateInvestorController {
  async handle(request: Request, response: Response) {
    const { name, email, password } = request.body;

    const createInvestorUseCase = container.resolve(CreateInvestorUseCase)

    const result = await createInvestorUseCase.execute({
      name,
      email,
      password,
    });

    return response.json(result);
  }
}

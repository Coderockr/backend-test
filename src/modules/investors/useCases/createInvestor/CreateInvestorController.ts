import { Request, Response } from "express";
import { CreateInvestorUseCase } from "./CreateInvestorUseCase";

export class CreateInvestorController {
  async handle(request: Request, response: Response) {
    const { name, email, password } = request.body;

    const createInvestorUseCase = new CreateInvestorUseCase();
    const result = await createInvestorUseCase.execute({
      name,
      email,
      password,
    });

    return response.json(result);
  }
}

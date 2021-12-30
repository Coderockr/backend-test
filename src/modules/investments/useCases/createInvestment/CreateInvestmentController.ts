import { Request, Response } from "express";
import { CreateInvestmentUseCase } from "./CreateInvestmentUseCase";

import { container } from 'tsyringe';

export class CreateInvestmentController {
  async handle(request: Request, response: Response) {
    const { created_at, capital } = request.body;
    const { id_investor } = request;

    const createInvestmentUseCase = container.resolve(CreateInvestmentUseCase);

    const investment = await createInvestmentUseCase.execute({
      id_investor,
      created_at,
      capital
    });

    return response.json(investment);
  }
}

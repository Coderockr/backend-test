import { container } from 'tsyringe';

import { Request, Response } from "express";
import { WithdrawnInvestmentUseCase } from './withdrawnInvestmentUseCase';

export class WithdrawnInvestmentController {
  async handle(request: Request, response: Response) {
    const { id_investor } = request;
    const { id } = request.params;
    const { withdraw_at } = request.body;

    const withdrawnInvestmentUseCase = container.resolve(WithdrawnInvestmentUseCase);

    const investment = await withdrawnInvestmentUseCase.execute({
      id_investor,
      id,
      withdraw_at
    });

    return response.json(investment);
  }
}

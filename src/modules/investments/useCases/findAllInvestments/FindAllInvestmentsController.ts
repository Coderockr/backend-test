import { container } from 'tsyringe';

import { Request, Response } from "express";
import { FindAllInvestmentsUseCase } from "./FindAllInvestmentsUseCase";

export class FindAllInvestmentsController {
  async handle(request: Request, response: Response) {
    const findAllInvestmentsUseCase = container.resolve(FindAllInvestmentsUseCase);

    const { id_investor } = request;
    const { page } = request.params;

    const investments = await findAllInvestmentsUseCase.execute({
      id_investor,
      page: parseInt(page)
    });

    return response.json(investments);
  }
}

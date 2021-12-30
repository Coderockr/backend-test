import { container } from 'tsyringe';

import { Request, Response } from "express";
import { UpdateWithdrawnUseCase } from "./UpdateWithdrawnUseCase";

export class UpdateWithdrawnController {
  async handle(request: Request, response: Response) {
    const { id_investor } = request;
    const { id } = request.params;
    const { withdraw_at } = request.body;

    const updateWithdrawnUseCase = container.resolve(UpdateWithdrawnUseCase);
    const delivery = await updateWithdrawnUseCase.execute({
      id_investor,
      id,
      withdraw_at
    });

    return response.json(delivery);
  }
}

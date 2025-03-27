import { container } from 'tsyringe';
import { Request, Response } from "express";
import { AuthenticateUseCase } from "./AuthenticateUseCase";

export class AuthenticateController {
  async handle(request: Request, response: Response) {
    const { email, password } = request.body;

    const authenticateUseCase = container.resolve(AuthenticateUseCase);
    const result = await authenticateUseCase.execute({
      email,
      password,
    });

    return response.json(result);
  }
}

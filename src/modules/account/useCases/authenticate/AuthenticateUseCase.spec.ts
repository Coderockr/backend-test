import "reflect-metadata";
import { InvestorRepositoryInMemory } from "@modules/investors/repositories/in-memory/InvestorRepositoryInMemory";
import { CreateInvestorUseCase } from "@modules/investors/useCases/createInvestor/CreateInvestorUseCase";
import { AuthenticateUseCase } from "./AuthenticateUseCase";

let authenticateUseCase: AuthenticateUseCase;
let createInvestorUseCase: CreateInvestorUseCase;

let investorRepositoryInMemory: InvestorRepositoryInMemory;

describe('Authenticate User', () => {

  beforeEach(() => {
    investorRepositoryInMemory = new InvestorRepositoryInMemory();
    authenticateUseCase = new AuthenticateUseCase(
      investorRepositoryInMemory,
    );
    createInvestorUseCase = new CreateInvestorUseCase(investorRepositoryInMemory);
  });

  it('should be able to authenticate an use', async () => {
    const investor = {
      name: "Jonh Doe",
      email: "user@email.com",
      password: "123456"
    };

    await createInvestorUseCase.execute(investor);

    const result = await authenticateUseCase.execute({
      email: investor.email,
      password: investor.password,
    });

    expect(result).toHaveProperty('token');
  });

  it('should no be able to authenticate an non existent user', async () => {
    await expect(
      authenticateUseCase.execute({
        email: 'false@email.com',
        password: '1234',
      }),
    ).rejects.toEqual(new Error('Email or password incorrect'));
  });

  it('should not be able to authenticate with incorrect password', async () => {
    const investor = {
      name: "Jonh Doe",
      email: "user@email.com",
      password: "123456"
    };

    await createInvestorUseCase.execute(investor);

    await expect(
      authenticateUseCase.execute({
        email: investor.email,
        password: 'incorrectPassword',
      }),
    ).rejects.toEqual(new Error('Email or password incorrect'));
  });
});

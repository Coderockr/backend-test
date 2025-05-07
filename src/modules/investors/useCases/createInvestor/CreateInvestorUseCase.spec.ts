import "reflect-metadata";
import { InvestorRepositoryInMemory } from "../../repositories/in-memory/InvestorRepositoryInMemory";
import { CreateInvestorUseCase } from "./CreateInvestorUseCase";


describe("Create Investor", () => {

  let createInvestorUseCase: CreateInvestorUseCase;
  let investorRepositoryInMemory: InvestorRepositoryInMemory;

  beforeEach(() => {
    investorRepositoryInMemory = new InvestorRepositoryInMemory();
    createInvestorUseCase = new CreateInvestorUseCase(investorRepositoryInMemory);
  })

  it('should be able to create a new investor', async () => {

    const investor = {
      name: "Investor name test",
      email: "investor2@email.com",
      password: "123456"
    }

    await createInvestorUseCase.execute(investor);

    const investorCreated = await investorRepositoryInMemory.findByEmail(investor.email);

    expect(investorCreated).toHaveProperty('id');

  });

  it('should not be able to create a new investor with email exists', async () => {

    const investor = {
      name: "Investor name test",
      email: "investor@email.com",
      password: "123456"
    }

    await createInvestorUseCase.execute(investor);

    await expect(
      createInvestorUseCase.execute(investor),
    ).rejects.toEqual(new Error('Investor already exists'));

  });
})
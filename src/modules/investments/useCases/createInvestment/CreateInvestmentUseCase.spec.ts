import "reflect-metadata";
import { InvestmentsRepositoryInMemory } from "@modules/investments/repositories/in-memory/InvestmentsRepositoryInMemory";
import { CreateInvestmentUseCase } from "./CreateInvestmentUseCase";
import { DayjsDateProvider } from "@shared/container/providers/DateProvider/implementations/DayjsDateProvider";
import { InvestorRepositoryInMemory } from "@modules/investors/repositories/in-memory/InvestorRepositoryInMemory";
import { CreateInvestorUseCase } from "@modules/investors/useCases/createInvestor/CreateInvestorUseCase";
import { ICreateInvestmentDTO } from "@modules/investments/dtos/ICreateInvestmentDTO";



describe("Create Investment", () => {

  let createInvestorUseCase: CreateInvestorUseCase;
  let createInvestmentUseCase: CreateInvestmentUseCase;
  let investmentsRepositoryInMemory: InvestmentsRepositoryInMemory;
  let dayjsDateProvider: DayjsDateProvider;
  let investorRepositoryInMemory: InvestorRepositoryInMemory;

  beforeEach(() => {
    investorRepositoryInMemory = new InvestorRepositoryInMemory();
    createInvestorUseCase = new CreateInvestorUseCase(investorRepositoryInMemory);
    dayjsDateProvider = new DayjsDateProvider();
    investmentsRepositoryInMemory = new InvestmentsRepositoryInMemory();
    createInvestmentUseCase = new CreateInvestmentUseCase(
      dayjsDateProvider,
      investmentsRepositoryInMemory,
      investorRepositoryInMemory);
  })

  it('should be able to create a new investment', async () => {

    const investor = {
      name: "Investor name test",
      email: "investor2@email.com",
      password: "123456"
    }

    await createInvestorUseCase.execute(investor);

    const investorCreated = await investorRepositoryInMemory.findByEmail(investor.email);

    const investment = {
      id_investor: investorCreated.id,
      created_at: dayjsDateProvider.dateNow(),
      capital: 1000.00
    }

    await createInvestmentUseCase.execute(investment);

    const investmentCreated = await investmentsRepositoryInMemory.findByIdInvestor(investment.id_investor);

    expect(investmentCreated).toHaveProperty('id');
  });

  it('the investor must be registered', async () => {

    expect(async () => {
      const investor = {
        name: "Investor name test",
        email: "investor2@email.com",
        password: "123456"
      }

      await createInvestorUseCase.execute(investor);

      const investorCreated = await investorRepositoryInMemory.findByEmail(investor.email);

      const investment = {
        id_investor: 'investor_invalid',
        created_at: dayjsDateProvider.dateNow(),
        capital: 1000.00
      }

      await createInvestmentUseCase.execute(investment);

      const investmentCreated = await investmentsRepositoryInMemory.findByIdInvestor(investment.id_investor);

    }).rejects.toBeInstanceOf(Error);

  });

  it('should not be able to create a new investment dated in the future', async () => {

    expect(async () => {

      const investor = {
        name: "Investor name test",
        email: "investor2@email.com",
        password: "123456"
      }

      await createInvestorUseCase.execute(investor);

      const investorCreated = await investorRepositoryInMemory.findByEmail(investor.email);

      const investment = {
        id_investor: investorCreated.id,
        created_at: dayjsDateProvider.addDays(1),
        capital: 1000.00
      }

      await createInvestmentUseCase.execute(investment);

      await investmentsRepositoryInMemory.findByIdInvestor(investment.id_investor);

    }).rejects.toBeInstanceOf(Error);
  });

  it('An investment should not be or become negative', async () => {

    expect(async () => {

      const investor = {
        name: "Investor name test",
        email: "investor2@email.com",
        password: "123456"
      }

      await createInvestorUseCase.execute(investor);

      const investorCreated = await investorRepositoryInMemory.findByEmail(investor.email);

      const investment = {
        id_investor: investorCreated.id,
        created_at: dayjsDateProvider.addDays(1),
        capital: -1000.00
      }

      await createInvestmentUseCase.execute(investment);

      await investmentsRepositoryInMemory.findByIdInvestor(investment.id_investor);

    }).rejects.toBeInstanceOf(Error);
  });

})
import "reflect-metadata";
import { InvestmentsRepositoryInMemory } from "@modules/investments/repositories/in-memory/InvestmentsRepositoryInMemory";
import { DayjsDateProvider } from "@shared/container/providers/DateProvider/implementations/DayjsDateProvider";
import { InvestorRepositoryInMemory } from "@modules/investors/repositories/in-memory/InvestorRepositoryInMemory";
import { CreateInvestorUseCase } from "@modules/investors/useCases/createInvestor/CreateInvestorUseCase";
import { ICreateInvestmentDTO } from "@modules/investments/dtos/ICreateInvestmentDTO";
import { WithdrawnInvestmentUseCase } from "./withdrawnInvestmentUseCase";
import { CalculateGainProvider } from "@shared/container/providers/CalculateGainProvider/implementations/CalculateGainProvider";



describe("Create Investment", () => {

  let dayjsDateProvider: DayjsDateProvider;
  let calculateGainProvider: CalculateGainProvider;
  let investmentsRepositoryInMemory: InvestmentsRepositoryInMemory;
  let withdrawnInvestmentUseCase: WithdrawnInvestmentUseCase;

  beforeEach(() => {
    dayjsDateProvider = new DayjsDateProvider();
    calculateGainProvider = new CalculateGainProvider();
    investmentsRepositoryInMemory = new InvestmentsRepositoryInMemory();
    withdrawnInvestmentUseCase = new WithdrawnInvestmentUseCase(
      dayjsDateProvider,
      calculateGainProvider,
      investmentsRepositoryInMemory
    );
  })

  it('It must allow the withdrawal of the investment', async () => {

    const investment = await investmentsRepositoryInMemory.create({
      id_investor: 'a94d921e-e512-4159-87c1-e388d88d9da1',
      created_at: dayjsDateProvider.addDays(-65),
      capital: 2000
    });

    const investmentToWithdrawn = {
      id: investment.id,
      id_investor: investment.id_investor,
      created_at: investment.created_at,
      capital: investment.capital,
      withdraw_at: dayjsDateProvider.dateNow(),
      withdraw_value: null,
      withdraw_rate: null
    }

    const toWithdrawnInvestment = await withdrawnInvestmentUseCase.execute(investmentToWithdrawn);

    expect(toWithdrawnInvestment.withdraw_at).not.toBe(null);
  });


  it('should be able to find an investment valid', async () => {

    expect(async () => {
      const investment = await investmentsRepositoryInMemory.create({
        id_investor: 'a94d921e-e512-4159-87c1-e388d88d9da1',
        created_at: dayjsDateProvider.addDays(-65),
        capital: 2000
      });

      const investmentToWithdrawn = {
        id: 'id investment invalid',
        id_investor: investment.id_investor,
        created_at: investment.created_at,
        capital: investment.capital,
        withdraw_at: dayjsDateProvider.dateNow(),
        withdraw_value: null,
        withdraw_rate: null
      }

      const toWithdrawnInvestment = await withdrawnInvestmentUseCase.execute(investmentToWithdrawn);

    }).rejects.toEqual(new Error('Investment not found'))

  });

})
import { ApolloError, UserInputError } from 'apollo-server-express'
import { Arg, Mutation, Query, Resolver } from 'type-graphql'
import {
  InvestmentModel,
  updateInvestmentGain
} from '../investment/InvestmentModel'
import {
  handleWithdrawalTax,
  Withdrawal,
  WithdrawalModel
} from './WithdrawalModel'
import { isBefore } from 'date-fns'
import { WithdrawalInput } from './WithdrawalType'

@Resolver()
export class WithDrawalResolver {
  @Mutation(() => Withdrawal)
  async withdrawalInvestment(
    @Arg('data') { investment_id, creationDate }: WithdrawalInput
  ): Promise<Withdrawal> {
    let existingInvestment = await InvestmentModel.findById(investment_id)
    if (!existingInvestment) {
      throw new UserInputError('Investment not found by the provided id')
    }

    if (isBefore(
        new Date(creationDate),
        new Date(existingInvestment.creationDate)
      )
    ) {
      throw new UserInputError('Creation date must not be in the future')
    }

    let updatedInvestment = await updateInvestmentGain(existingInvestment)

    const tax = handleWithdrawalTax(
      updatedInvestment.creationDate,
      +updatedInvestment.gainAmount
    ) as number

    const finalValue = +updatedInvestment?.currentAmount - tax

    const withdrawal = await WithdrawalModel.create({
      tax,
      finalValue,
      creationDate,
      investment_id
    })

    updatedInvestment.withdrawal = true
    await updatedInvestment.save()

    await withdrawal.save()
    return withdrawal
  }

  @Query(() => Withdrawal)
  async getWithdrawalByInvestment(@Arg('id') id: string){
    const investment = await InvestmentModel.findById(id);
    if(!investment) {
      throw new ApolloError("Investment not found by the provided id.");
    }

    const withdrawal = await WithdrawalModel.findOne({ investment_id: id });

    return withdrawal
  }

  @Query(() => [Withdrawal])
  async returnAllWithdrawals() {
    return await WithdrawalModel.find()
  }
}

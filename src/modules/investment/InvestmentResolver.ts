import { Arg, Mutation, Query, Resolver } from "type-graphql";
import { Investment, InvestmentModel, updateInvestmentGain } from "./InvestmentModel";
import { UserInputError, ApolloError } from "apollo-server-errors";
import { InvestmentInput } from "./InvestmentType";
import { UserModel } from "../user/UserModel";

@Resolver()
export class InvestmentResolver {

  @Mutation(() => Investment)
  async createInvestment(
    @Arg("data")
    { initialAmount, creationDate, user_id }: InvestmentInput
  ): Promise<Investment> {

    const investment = await InvestmentModel.create({
      initialAmount,
      creationDate,
      user_id,
    });
    await investment.save();
    return investment;
  }

  @Query(() => Investment)
  async getInvestmentByid(@Arg('id') id: string){
    const investment = await InvestmentModel.findById(id);
    if(!investment) {
      throw new ApolloError("Investment not found by the provided id.");
    }

    return updateInvestmentGain(investment)
  }

  @Query(() => [Investment])
  async returnAllInvestmentsByUser(@Arg('user_id') user_id: string) {
    const existingUser = await UserModel.findById(user_id);
    if(!existingUser) {
      throw new UserInputError("User not found by the provided id.");      
    }

    const investments = await InvestmentModel.find({ user_id });
    return investments.length ? investments.map(updateInvestmentGain) : investments;
  }

  @Mutation(() => Investment)
  async deleteInvestment(@Arg('id') id: string){
    const investment = await InvestmentModel.findById(id);
    if(!investment) {
      throw new UserInputError("Investment not found by the provided id.");
    }

    await investment.remove();
    return investment
  }
}

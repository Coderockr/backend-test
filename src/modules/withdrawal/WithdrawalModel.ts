import { Field, ID, ObjectType } from "type-graphql";
import { getModelForClass, prop as Property } from '@typegoose/typegoose'
import { Investment } from "../investment/InvestmentModel";
import { Ref } from "../../entities/types";
import { differenceInYears } from "date-fns";

export const LESS_YEAR_TAX = 22.5
export const ONE_YEAR_TAX = 18.5
export const TWO_YEAR_TAX = 15

@ObjectType({ description: "Withdrawal" })
export class Withdrawal {
    @Field(() => ID)
    id: string;

    @Field()
    @Property({ required: true })
    creationDate: Date

    @Field()
    @Property({ default: 0, min: 0 })
    tax: Number

    @Field()
    @Property({ default: 0, min: 0 })
    finalValue: Number

    @Field((_type) => String)
    @Property({ ref: Investment })
    investment_id: Ref<Investment>
}

export const handleWithdrawalTax = (creationDate: Date, gain: number) => {
    let difference = differenceInYears(new Date(), new Date(creationDate))

    difference = difference > 2 ? 2 : difference

    const dateMapper = {
      0: (LESS_YEAR_TAX * gain) / 100,
      1: (ONE_YEAR_TAX * gain) / 100,
      2: (TWO_YEAR_TAX * gain) / 100
    }

    return dateMapper[difference as keyof typeof dateMapper] ?? 0
  }

export const WithdrawalModel = getModelForClass(Withdrawal)
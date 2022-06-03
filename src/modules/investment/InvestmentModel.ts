import { Field, ID, ObjectType } from "type-graphql";
import { Document } from 'mongoose'
import { getModelForClass, prop as Property } from '@typegoose/typegoose'
import { User } from "../user/UserModel";
import { Ref } from "../../entities/types";
import { differenceInMonths } from "date-fns";

export const MONTLY_GAIN_FEE = 0.52

type MongoType<T = {}> = Document & T

@ObjectType({ description: "Investment" })
export class Investment {
    @Field(() => ID)
    id: string

    @Field()    
    @Property({ required: true, min: 0 })
    initialAmount: Number

    @Field()
    @Property({ required: true, default: 0, min: 0})
    currentAmount: Number

    @Field()
    @Property({ required: true, default: 0, min: 0})
    gainAmount: Number

    @Field()
    @Property({ required: true, default: false })
    withdrawal: Boolean

    @Field()
    @Property({ required: true })
    creationDate: Date

    @Field((_type) => String)
    @Property({ ref: User })
    user_id: Ref<User>
}

export const updateInvestmentGain = async (investment: MongoType<Investment>): Promise<MongoType<Investment>> => {
  const gain = calculateCurrentGains(
    +investment.initialAmount, 
    investment.creationDate)

  investment.currentAmount = (+investment.initialAmount) + gain;
  investment.gainAmount = gain;
  return (await investment.save())
}

export const calculateCurrentGains = (initialAmount: number, creationDate: Date) => {
    const pastMonths = differenceInMonths(new Date(), new Date(creationDate));
    const newGain = (+initialAmount * (pastMonths * MONTLY_GAIN_FEE)) / 100

    return newGain;
  }

export const InvestmentModel = getModelForClass(Investment)
import { Field, InputType } from "type-graphql";
import { Withdrawal } from "./WithdrawalModel";
import { ObjectId } from "mongodb";
import { MaxDate } from "class-validator";
import { addHours } from "date-fns";

@InputType()
export class WithdrawalInput implements Partial<Withdrawal> {
  @Field()
  @MaxDate(addHours(new Date(), 1))
  creationDate: Date;

  @Field(() => String)
  investment_id: ObjectId;
}

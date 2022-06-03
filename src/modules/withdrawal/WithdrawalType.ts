import { Field, InputType } from "type-graphql";
import { Withdrawal } from "./WithdrawalModel";
import { ObjectId } from "mongodb";
import { MaxDate } from "class-validator";

@InputType()
export class WithdrawalInput implements Partial<Withdrawal> {
  @Field()
  @MaxDate(new Date())
  creationDate: Date;

  @Field(() => String)
  investment_id: ObjectId;
}


import { Field, InputType } from "type-graphql";
import { Investment } from "./InvestmentModel";
import { ObjectId } from 'mongodb'
import { IsDate, MaxDate, Min } from "class-validator";
import { addHours } from "date-fns";

@InputType()
export class InvestmentInput implements Partial<Investment> {    
    @Min(0)
    @Field()
    initialAmount: number;
    
    @Field()
    @IsDate()
    @MaxDate(addHours(new Date(), 1))
    creationDate: Date;

    @Field(() => String)
    user_id: ObjectId;

}

import { Field, InputType } from "type-graphql";
import { Investment } from "./InvestmentModel";
import { ObjectId } from 'mongodb'
import { IsDate, MaxDate, Min } from "class-validator";

@InputType()
export class InvestmentInput implements Partial<Investment> {    
    @Min(0)
    @Field()
    initialAmount: number;
    
    @Field()
    @IsDate()
    @MaxDate(new Date())
    creationDate: Date;

    @Field(() => String)
    user_id: ObjectId;

}
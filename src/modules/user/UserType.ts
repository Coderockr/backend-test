import { Field, InputType } from "type-graphql";
import { User } from "./UserModel";
import { IsDate, IsEmail, MaxLength } from 'class-validator'
 
@InputType()
export class UserInput implements Partial<User> {

    @MaxLength(128)
    @Field()
    name: string;
    
    @Field()
    @IsEmail()
    email: string;

    @Field()
    @IsDate()
    bornDate: Date;
}
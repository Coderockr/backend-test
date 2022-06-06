import { Field, ID, ObjectType } from "type-graphql";
import { getModelForClass, prop as Property } from '@typegoose/typegoose'
import { IsEmail } from "class-validator";

@ObjectType({ description: "User" })
export class User {
    @Field(() => ID)
    id: string

    @Field()
    @Property({ required: true })
    name: String;

    @Field()
    @IsEmail()
    @Property({ required: true, unique: true })
    email: String;

    @Field()
    @Property({ required: true })
    bornDate: Date

}

export const UserModel = getModelForClass(User)
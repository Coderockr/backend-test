import { Arg, Mutation, Resolver } from "type-graphql";
import { User, UserModel } from "./UserModel";
import { UserInput } from "./UserType";
import { isFuture } from 'date-fns'
import { UserInputError } from 'apollo-server-errors'

@Resolver()
export class UserResolver {
    @Mutation(() => User)
    async createUser(@Arg('data') { name, email, bornDate }: UserInput): Promise<User> {
        if(isFuture(new Date(bornDate))) {
            throw new UserInputError('Born date must not be in the future');
        }

        const existingUser = await UserModel.findOne({ email });
        if(existingUser) {
            throw new UserInputError('User already exists by the provided email.');
        }

        const user = await UserModel.create({
            name,
            email,
            bornDate
        });
        await user.save();
        return user;
    }

  @Mutation(() => User)
  async deleteUser(@Arg('id') id: string){
    const user = await UserModel.findById(id);
    if(!user) {
      throw new UserInputError("User not found by the provided id.");
    }

    await user.remove();
    return user
  }
}
import { mutationWithClientMutationId } from 'graphql-relay';
import { GraphQLString, GraphQLNonNull } from 'graphql';
import bcrypt from 'bcryptjs';

import UserModel from '../UserModel';
import * as UserLoader from '../UserLoader'
import { UserType } from '../UserType';

import { generateJwtToken } from '../../../auth';

export const userLogin = mutationWithClientMutationId({
  name: 'UserLogin',
  inputFields: {
    email: { type: new GraphQLNonNull(GraphQLString) },
    password: { type: new GraphQLNonNull(GraphQLString) },
  },
  mutateAndGetPayload: async ({ email, password }, context) => {
    const user = await UserModel.findOne({ email: email.trim().toLowerCase() });

    if (!user) {
      return {
        error: ('This user was not registered. Please, try again!')
      }
    }

    if (!bcrypt.compareSync(password, user.password)) {
      return {
        error: ('Password incorrect, please try again!')
      }
    }

    const token = generateJwtToken(user._id);
    context.setCookie("login", token)

    return {
      token,
      user,
    };
  },
  outputFields: {
    token: {
      type: GraphQLString,
      resolve: ({ token }) => token,
    },
    me: {
      type: UserType,
      resolve: async ({ user }, _, context) => {
        return await UserLoader.load(context, user._id)
      },
    },
  },
});

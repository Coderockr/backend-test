import { GraphQLFloat, GraphQLNonNull, GraphQLString } from 'graphql';
import { mutationWithClientMutationId, toGlobalId } from 'graphql-relay';
import { GraphQLContext } from '../../../types/types';
import InvestmentModel from '../InvestmentModel';
import { InvestmentEdge } from '../InvestmentType';
import * as InvestmentLoader from '../InvestmentLoader'

const mutation = mutationWithClientMutationId({
  name: 'InvestmentCreate',
  description: "Create a new Investment",
  inputFields: {
    name: {
      type: new GraphQLNonNull(GraphQLString),
    },
    amount: {
      type: new GraphQLNonNull(GraphQLFloat),
    },
    initialBalance: {
      type: new GraphQLNonNull(GraphQLFloat),
    },
  },
  mutateAndGetPayload: async ({ name, amount, initialBalance }, context: GraphQLContext) => {

    if (!context.user) {
      return {
        error: 'user not logged in'
      }
    }

    const investment = await new InvestmentModel({
      name,
      owner: context.user._id,
      amount,
      initialBalance,
    }).save();

    return {
      error: null,
      success: 'investment created',
      id: investment._id
    };
  },
  outputFields: {
  investmentEdge: {
      type: InvestmentEdge,
      resolve: async ({ id }, _, context) => {
        const investment = await InvestmentLoader.load(context, id)

        if (!investment) {
          return null;
        }

        return {
          cursor: toGlobalId('Investment', investment._id),
          node: investment,
        };
      },
    },
    error: {
      type: GraphQLString,
      resolve: response => response.error
    },
    success: {
      type: GraphQLString,
      resolve: response => response.success
    }
  },
});

export default mutation;

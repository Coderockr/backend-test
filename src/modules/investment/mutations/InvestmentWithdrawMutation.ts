import { GraphQLNonNull, GraphQLString } from 'graphql';
import { mutationWithClientMutationId, toGlobalId } from 'graphql-relay';
import { GraphQLContext } from '../../../types/types';
import InvestmentModel from '../InvestmentModel';
import { InvestmentEdge } from '../InvestmentType';
import * as InvestmentLoader from '../InvestmentLoader'
import { getGain } from '../utils/getGain';

const mutation = mutationWithClientMutationId({
  name: 'InvestmentWithDraw',
  description: "Withdraw an Investment",
  inputFields: {
    name: {
      type: new GraphQLNonNull(GraphQLString),
    }
  },
  mutateAndGetPayload: async ({ name }, context: GraphQLContext) => {

    if (!context.user) {
      return {
        error: 'user not logged in'
      }
    }

    const investment = await InvestmentModel.findOne({
      name: name
    })

    const monthsPassed = await getGain(investment)
    const profit = investment.amount - investment.initialBalance

    if (monthsPassed.create < 12) {
      const taxes = profit * 1.0225
      await InvestmentModel.findByIdAndUpdate(investment.id, {
        $set: {
          amount: 0,
          withdraw: investment.amount - taxes
        }
      })

      return {
        error: null,
        success: 'investment withdrawed with success',
        id: investment._id
      }
    } else if (monthsPassed.create > 12 || monthsPassed.create <= 24) {
      const taxes = profit * 1.0185
      await InvestmentModel.findByIdAndUpdate(investment.id, {
        $set: {
          amount: 0,
          withdraw: investment.amount - taxes
        }
      })

      return {
        error: null,
        success: 'investment withdrawed with success',
        id: investment._id
      }
    } else {
      const taxes = profit * 1.015
      await InvestmentModel.findByIdAndUpdate(investment.id, {
        $set: {
          amount: 0,
          withdraw: investment.amount - taxes
        }
      })

      return {
        error: null,
        success: 'investment withdrawed with success',
        id: investment._id
      }
    }
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

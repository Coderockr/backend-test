/* eslint-disable @typescript-eslint/ban-ts-comment */

import { GraphQLObjectType } from 'graphql';
import { InvestmentConnection } from '../modules/investment/InvestmentType';
import { nodeField, nodesField } from '../modules/node/typeRegister';
import * as InvestmentLoader from '../modules/investment/InvestmentLoader'
import * as UserLoader from '../modules/user/UserLoader'
import { connectionArgs, withFilter } from '@entria/graphql-mongo-helpers';
import { UserType } from '../modules/user/UserType';
import { getGain } from '../modules/investment/utils/getGain';
import InvestmentModel from '../modules/investment/InvestmentModel';

const QueryType = new GraphQLObjectType({
  name: 'Query',
  description: 'The root of all queries',
  fields: () => ({
    node: nodeField,
    nodes: nodesField,
    investments: {
      type: InvestmentConnection,
      args: {
        ...connectionArgs
      },
      resolve: async (_, args, context) => {
        if(!context.user) {
          return {
            error: 'User not logged in'
          }
        }

        const ownerFilter = withFilter(args, {
          owner: context.user._id
        })

        const investments = await InvestmentLoader.loadAll(context, ownerFilter)

        investments.edges.map(async (edge) => {
          const node = await edge.node
          const getMonthsPassed = await getGain(node)

          if(getMonthsPassed.create > 0 || getMonthsPassed.update > 0) {

            await InvestmentModel.findByIdAndUpdate(node._id, {
              $set: {
                // add gain calculation
                amount: node.amount * 1.0052
              }
            })
          }

          InvestmentLoader.clearCache(context, node._id)
        })
        return InvestmentLoader.loadAll(context, ownerFilter)

      }
    },
    user: {
      type: UserType,
      resolve: async (_, args, context) => {
        return await UserLoader.load(context, context.user?._id)
      }
    }
  })
})
export default QueryType

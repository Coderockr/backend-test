import { GraphQLObjectType, GraphQLString, GraphQLNonNull, GraphQLFloat } from "graphql";
import { connectionDefinitions, globalIdField } from "graphql-relay";
import { nodeInterface, registerTypeLoader } from "../node/typeRegister";
import { UserType } from "../user/UserType";
import { load } from "./InvestmentLoader";
import * as UserLoader from '../user/UserLoader'
import type { IInvestment } from "./InvestmentModel";
import { GraphQLContext } from "../../types/types";

export const InvestmentType = new GraphQLObjectType<IInvestment, GraphQLContext>({
  name: "Investment",
  description: "Investment",
  fields: () => ({
    id: globalIdField("Investment"),
    name: {
      type: new GraphQLNonNull(GraphQLString),
      resolve: ({ name }) => name,
    },
    owner: {
      type: UserType,
      resolve: (investment, _, context) => UserLoader.load(context, investment.owner)
    },
    amount: {
      type: new GraphQLNonNull(GraphQLFloat),
      resolve: ({ amount }) => amount,
    },
    initialBalance: {
      type: new GraphQLNonNull(GraphQLFloat),
      resolve: ({ initialBalance }) => initialBalance,
    },
    createdAt : {
      type : new GraphQLNonNull(GraphQLString),
      //resolve: ({ createdAt }) => createdAt,
    },
    updatedAt : {
      type : new GraphQLNonNull(GraphQLString),
      //resolve: ({ updatedAt }) => updatedAt,
    },
    withdraw: {
      type: GraphQLFloat,
      resolve: ({ withdraw }) => withdraw
    }
  }),
  interfaces: () => [nodeInterface]
});

registerTypeLoader(InvestmentType, load)

export const { connectionType: InvestmentConnection, edgeType: InvestmentEdge } = connectionDefinitions({
  name: "Investment",
  nodeType: InvestmentType,
});

export default InvestmentType

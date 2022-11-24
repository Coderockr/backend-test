import { GraphQLObjectType } from 'graphql';

import InvestmentMutations from '../modules/investment/mutations';
import UserMutations from '../modules/user/mutations'

export default new GraphQLObjectType({
  name: 'Mutation',
  description: "Root of mutations",
  fields: () => ({
    ...InvestmentMutations,
    ...UserMutations
  }),
});

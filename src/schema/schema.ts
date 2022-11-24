import { GraphQLSchema } from 'graphql';

import QueryType from './QueryType';
import MutationType from './MutationType';

export const schema = new GraphQLSchema({
  query: QueryType,
  mutation: MutationType,
});

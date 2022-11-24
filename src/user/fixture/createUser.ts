import User from '../UserModel';
import { getCounter } from '../../../../test';

export const createUser = () => {
  const i = getCounter('user');

  return new User({
    username: `user#${i}`,
    email: `user${i}@example.com`,
    password: `pass${i}`,
  }).save();
};

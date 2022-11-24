import { DataLoaders } from "../modules/loader/loaderRegister";
import { IUser } from "../modules/user/UserModel";

export type GraphQLContext = {
  user?: IUser;
  dataloaders: DataLoaders;
}

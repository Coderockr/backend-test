import jwt from 'jsonwebtoken'
import { jwtSecret } from './config';
import UserModel, { IUser } from './modules/user/UserModel';

export const getUser = async (token: string | null | undefined) => {
  if (!token) return { user: null};

  try {
    const decodedToken = jwt.verify(token.substring(4), jwtSecret)

    const user = await UserModel.findOne({_id: (decodedToken as { id:string }).id })

    return {
      user
    }
  } catch(err) {
    return { user: null}
  }

}

export const generateJwtToken = (user: IUser) => {
  return `JWT ${jwt.sign({ id: user._id }, `${jwtSecret}`)}`
}

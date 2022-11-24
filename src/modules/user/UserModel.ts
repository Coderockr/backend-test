import mongoose, { Document, Model, Schema } from 'mongoose';
interface IUser {
  username: string;
  email: string;
  password: string;
}

export type User = Document & IUser

const UserSchema = new Schema<IUser>(
  {
    username: {
      type: String,
      required: true,
      unique: true
    },
    email: {
      type: String,
      required: true,
      unique: true
    },
    password: {
      type: String,
      required: true
    }
  },
  {
    collection: 'User',
    timestamps: true,
  },
);

const UserModel: Model<IUser> = mongoose.model('User', UserSchema);

export default UserModel;

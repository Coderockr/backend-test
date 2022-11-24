import mongoose, { Document, Model, Schema, Types } from 'mongoose';
const { ObjectId } = mongoose.Schema.Types

export interface IInvestment {
  name: string;
  owner: Types.ObjectId
  amount: number;
  initialBalance: number;
  withdraw: number
  createdAt: Date;
  updatedAt: Date;
}

export type Investment = Document & IInvestment

const InvestmentSchema = new Schema<Investment>(
  {
    name: {
      type: String,
      required: true,
      unique: true
    },
    owner: {
      type: ObjectId,
      ref: 'User',
      required: true
    },
    amount: {
      type: Number,
      required: true
    },
    initialBalance: {
      type: Number,
      required: true
    },
    withdraw: {
      type: Number,
      required: false
    },
    createdAt: {
      type: Date,
      index: true,

      es_type: 'date',
      es_indexed: true,
    },
    updatedAt: {
      type: Date,
      index: true,

      es_type: 'date',
      es_indexed: true,
    },
  },
  {
    collection: 'Investment',
    timestamps: {
      createdAt: 'createdAt',
      updatedAt: 'updatedAt'
    },
  },
);

const InvestmentModel: Model<Investment> = mongoose.model('Investment', InvestmentSchema);

export default InvestmentModel;

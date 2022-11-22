import { OwnerEntity } from './../../owners/entities/owner.entity';
import { Investiment } from '@prisma/client';
import { Decimal } from '@prisma/client/runtime';

export class InvestmentEntity implements Investiment {
  id: number;
  owner_id: number;
  creation_date: Date;
  amount: Decimal;
  initial_amount: Decimal;
  expected_balance: Decimal;
  active: boolean;
  owner?: OwnerEntity;
}

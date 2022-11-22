import { Owner } from '@prisma/client';

export class OwnerEntity implements Owner {
  id: number;
  name: string;
  email: string;
}

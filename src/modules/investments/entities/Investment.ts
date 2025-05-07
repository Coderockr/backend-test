import { v4 as uuidV4 } from 'uuid';

class Investment {
  id: string;
  id_investor: string;
  created_at: Date;
  capital?: number;
  withdraw_at?: Date;
  withdraw_value?: number;
  withdraw_rate?: number;

  constructor() {
    if (!this.id) {
      this.id = uuidV4();
    }
  }
}

export { Investment }
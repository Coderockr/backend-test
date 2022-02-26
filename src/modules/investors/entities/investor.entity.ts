import { Investment } from '../../../modules/investments/entities/investment.entity';
import {
  Column,
  Entity,
  OneToMany,
  BaseEntity,
  PrimaryGeneratedColumn,
} from 'typeorm';

@Entity({ name: 'investors' })
export class Investor extends BaseEntity {
  @PrimaryGeneratedColumn()
  id: number;

  @Column()
  name: string;

  @OneToMany(() => Investment, (investment) => investment.investor)
  investments: Investment;
}

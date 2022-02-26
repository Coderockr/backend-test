import { IsNumber } from 'class-validator';
import { Investor } from '../../../modules/investors/entities/investor.entity';
import {
  Column,
  JoinColumn,
  ManyToOne,
  Entity,
  PrimaryGeneratedColumn,
  BaseEntity,
  RelationId,
} from 'typeorm';

@Entity({ name: 'investments' })
export class Investment extends BaseEntity {
  @PrimaryGeneratedColumn()
  id: number;

  @Column({ name: 'reference_date', type: 'date' })
  reference_date: Date;

  @Column({ name: 'amount' })
  @IsNumber()
  amount: number;

  @ManyToOne(() => Investor, (investor) => investor.investments)
  @JoinColumn({ name: 'investor_id' })
  investor: Promise<Investor>;

  @Column()
  @RelationId((investment: Investment) => investment.investor)
  investor_id: number;

  constructor(investment?: Partial<Investment>) {
    super();
    this.reference_date = investment?.reference_date;
    this.amount = investment?.amount;
    this.investor_id = investment?.investor_id;

    return;
  }
}

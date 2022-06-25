import { Column, DataType, Model, PrimaryKey, Table } from "sequelize-typescript";

@Table({
    tableName: 'investments',
  })
export class Investment extends Model {
    @PrimaryKey
    @Column({ type: DataType.UUID, defaultValue: DataType.UUIDV4 })
    id: string;

    @Column({ allowNull: false, type: DataType.STRING })
    owner: string;

    @Column({ allowNull: false, type: DataType.DECIMAL(10, 2) })
    amount: number;

    @Column({ allowNull: false, type: DataType.DATE})
    date: string;
}
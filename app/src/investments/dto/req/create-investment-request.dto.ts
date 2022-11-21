import { ApiProperty } from '@nestjs/swagger';
import { Type } from 'class-transformer';
import { IsDate, IsNumber, IsPositive, MaxDate } from 'class-validator';

export class CreateInvestmentRequestDto {
  @ApiProperty()
  @IsNumber()
  owner_id: number;

  @ApiProperty()
  @MaxDate(new Date(new Date().setHours(23, 59, 59, 999)))
  @IsDate({ message: 'invalid date format, expect: YYYY-MM-DD' })
  @Type(() => Date)
  creation_date: Date;

  @ApiProperty()
  @IsNumber(
    { allowInfinity: false, allowNaN: false },
    { message: 'amount must be a number' },
  )
  @IsPositive({ message: 'amount must be a positive number' })
  amount: number;

  expected_balance?: number;
}

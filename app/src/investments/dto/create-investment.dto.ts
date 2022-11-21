import { ApiProperty } from '@nestjs/swagger';
import { Type } from 'class-transformer';
import {
  IsDate,
  IsDateString,
  IsNumber,
  IsOptional,
  IsPositive,
  MaxDate,
  maxDate,
  MinDate,
} from 'class-validator';

export class CreateInvestmentDto {
  @ApiProperty()
  @IsOptional()
  id?: number;

  @ApiProperty()
  @IsNumber()
  owner_id: number;

  @ApiProperty()
  @IsDateString()
  @MaxDate(new Date())
  @Type(() => Date)
  creation_date: Date;

  @ApiProperty()
  @IsNumber(
    { allowInfinity: false, allowNaN: false },
    { message: 'amount must be a number' },
  )
  @IsPositive({ message: 'amount must be a positive number' })
  amount: number;
}

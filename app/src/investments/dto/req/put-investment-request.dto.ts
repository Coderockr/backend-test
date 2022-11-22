import { ApiProperty } from '@nestjs/swagger';
import { Type } from 'class-transformer';
import { IsDate, MaxDate } from 'class-validator';

export class PutInvestmentRequestDto {
  @ApiProperty()
  @MaxDate(new Date(new Date().setHours(23, 59, 59, 999)))
  @IsDate({ message: 'invalid date format, expect: YYYY-MM-DD' })
  @Type(() => Date)
  withdrawal_date: Date;
}

import { ApiProperty } from '@nestjs/swagger';
import { IsDateString, Min } from 'class-validator';
import * as moment from 'moment';

export class CreateInvestmentDto {
  @ApiProperty({ required: true })
  @Min(0)
  amount: number;

  @ApiProperty({
    required: true,
    type: Date,
    default: moment().format(moment.HTML5_FMT.DATE),
  })
  @IsDateString()
  reference_date: Date;

  @ApiProperty({ required: true })
  investor_name: string;
}

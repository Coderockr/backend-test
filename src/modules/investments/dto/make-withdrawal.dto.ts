import { ApiProperty } from '@nestjs/swagger';
import { IsDateString, Min } from 'class-validator';
import * as moment from 'moment';

export class MakeWithdrawalDto {
  @ApiProperty({ required: true })
  investment_id: number;

  @ApiProperty({ required: true })
  amount: number;

  @ApiProperty({
    required: true,
    type: Date,
    default: moment().format(moment.HTML5_FMT.DATE),
  })
  @IsDateString()
  date: Date;
}

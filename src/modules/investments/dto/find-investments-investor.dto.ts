import { ApiProperty } from '@nestjs/swagger';

export class FindInvestmentsInvestorDto {
  @ApiProperty({ required: true })
  investor_name: string;
}

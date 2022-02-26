import { ApiProperty } from '@nestjs/swagger';
import { Min } from 'class-validator';

export class PaginationDto {
  @ApiProperty({ default: 1 })
  page: number;

  @ApiProperty({ default: 10 })
  limit: number;
}

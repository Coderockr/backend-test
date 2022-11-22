import { PartialType } from '@nestjs/mapped-types';
import { CreateInvestmentRequestDto } from './create-investment-request.dto';

export class UpdateInvestmentRequestDto extends PartialType(
  CreateInvestmentRequestDto,
) {}

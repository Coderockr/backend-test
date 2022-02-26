import { Investment } from 'src/modules/investments/entities/investment.entity';

export class PaginatedInvestmentsResultDto {
  data: Investment[];
  page: number;
  limit: number;
  totalCount: number;
}

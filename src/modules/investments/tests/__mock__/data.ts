import * as moment from 'moment';
import { PaginationDto } from 'src/modules/common/interfaces/pagination.dto';
import { PaginatedInvestmentsResultDto } from '../../dto/paginated-investments-result.dto';
import { CreateInvestmentDto } from '../../dto/create-investment.dto';
import { Investment } from '../../entities/investment.entity';
import { InvestmentResultDto } from '../../dto/find-investment-result.dto';
import { WithdrawalResultDto } from '../../dto/withdrawal-result.dto';

export const investment: CreateInvestmentDto = {
  amount: 10,
  reference_date: new Date(moment().format('YYYY-MM-DD')),
  investor_name: 'John Snow',
};

export const investmentsPaginated: PaginatedInvestmentsResultDto = {
  data: [
    new Investment({
      investor_id: 1,
      amount: 10,
      reference_date: new Date(moment().format('YYYY-MM-DD')),
    }),
    new Investment({
      investor_id: 2,
      amount: 11,
      reference_date: new Date(moment().format('YYYY-MM-DD')),
    }),
  ],
  page: 1,
  limit: 10,
  totalCount: 2,
};

export const investmentWithGains: InvestmentResultDto = {
  initial_amount: '100.00',
  expected_balance: '101.57',
};

export const withdrawal: WithdrawalResultDto = {
  date: new Date(moment().subtract(1, 'days').format('YYYY-MM-DD')),
  withdrawal_amount: '101.57',
};

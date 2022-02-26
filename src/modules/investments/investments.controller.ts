import { Controller, Get, Post, Body, Param, Query } from '@nestjs/common';
import { ApiOkResponse, ApiTags } from '@nestjs/swagger';
import { InvestmentsService } from './investments.service';
import { CreateInvestmentDto } from './dto/create-investment.dto';
import { FindInvestmentsInvestorDto } from './dto/find-investments-investor.dto';
import { PaginationDto } from '../common/interfaces/pagination.dto';
import { MakeWithdrawalDto } from './dto/make-withdrawal.dto';
import { PaginatedInvestmentsResultDto } from './dto/paginated-investments-result.dto';
import { WithdrawalResultDto } from './dto/withdrawal-result.dto';
import { InvestmentResultDto } from './dto/find-investment-result.dto';

@ApiTags('investimentos')
@Controller('investments')
export class InvestmentsController {
  constructor(private readonly investmentsService: InvestmentsService) {}

  @Post()
  create(@Body() createInvestmentDto: CreateInvestmentDto) {
    return this.investmentsService.create(createInvestmentDto);
  }

  @Get()
  @ApiOkResponse({
    description: 'Returns all investments of an investor',
    type: PaginatedInvestmentsResultDto,
  })
  findByInvestor(
    @Query() { investor_name }: FindInvestmentsInvestorDto,
    @Query() paginationDto: PaginationDto,
  ) {
    paginationDto.page = paginationDto.page ? Number(paginationDto.page) : 0;
    paginationDto.limit = Number(paginationDto.limit);

    return this.investmentsService.findByInvestor(
      {
        ...paginationDto,
        limit: paginationDto.limit > 10 ? 10 : paginationDto.limit,
      },
      investor_name,
    );
  }

  @Get('/gains/:investment_id')
  @ApiOkResponse({
    description: 'Returns an investment with its earning potential',
    type: InvestmentResultDto,
  })
  findInvestmentGains(@Param('investment_id') id: number) {
    return this.investmentsService.findInvestmentGains(+id);
  }

  @Get('/withdrawal')
  @ApiOkResponse({
    description:
      'Performs a withdrawal from the wallet and returns its value and date',
    type: WithdrawalResultDto,
  })
  makeWithdrawal(@Query() makeWithdrawalDto: MakeWithdrawalDto) {
    return this.investmentsService.makeWithdrawal(makeWithdrawalDto);
  }
}

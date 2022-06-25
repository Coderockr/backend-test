import { Body, Controller, Get, Param, Patch, Post, Query } from '@nestjs/common';

import { CreateInvestmentDto } from './dto/create-investment.dto';
import { ListParamsDto } from './dto/list-params.dto';
import { WithdrawalInvestmentDto } from './dto/withdrawal-investment.dto';
import { InvestmentService } from './investment.service';

@Controller('investment')
export class InvestmentController {
  constructor(private readonly investmentService: InvestmentService) {}

  @Post()
  create(@Body() createInvestmentDto: CreateInvestmentDto) {
    return this.investmentService.create(createInvestmentDto);
  }

  @Get()
  findAll() {
    return this.investmentService.findAll();
  }

  @Get('/view/:id')
  view(@Param('id') id: string) {
    return this.investmentService.view(id);
  }

  @Get('/list')
  list(@Query() params: ListParamsDto) {
    return this.investmentService.list(params);
  }

  @Patch('/withdrawal')
  withdrawal(@Body() withdrawalInvestmentDto: WithdrawalInvestmentDto) {
    return this.investmentService.withdrawal(withdrawalInvestmentDto);
  }

}

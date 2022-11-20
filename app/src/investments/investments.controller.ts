import { Controller, Get, Post, Body, Patch, Param, Delete } from '@nestjs/common';
import { InvestmentsService } from './investments.service';
import { CreateInvestmentDto } from './dto/create-investment.dto';
import { UpdateInvestmentDto } from './dto/update-investment.dto';

@Controller('investments')
export class InvestmentsController {
  constructor(private readonly investmentsService: InvestmentsService) {}

  @Post()
  create(@Body() createInvestmentDto: CreateInvestmentDto) {
    return this.investmentsService.create(createInvestmentDto);
  }

  @Get()
  findAll() {
    return this.investmentsService.findAll();
  }

  @Get(':id')
  findOne(@Param('id') id: string) {
    return this.investmentsService.findOne(+id);
  }

  @Patch(':id')
  update(@Param('id') id: string, @Body() updateInvestmentDto: UpdateInvestmentDto) {
    return this.investmentsService.update(+id, updateInvestmentDto);
  }

  @Delete(':id')
  remove(@Param('id') id: string) {
    return this.investmentsService.remove(+id);
  }
}

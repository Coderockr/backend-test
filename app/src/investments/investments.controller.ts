import { InvestmentEntity } from './entities/investment.entity';
import {
  Controller,
  Get,
  Post,
  Body,
  Patch,
  Param,
  Delete,
} from '@nestjs/common';
import { InvestmentsService } from './investments.service';
import { CreateInvestmentRequestDto } from './dto/req/create-investment-request.dto';
import { UpdateInvestmentRequestDto } from './dto/req/update-investment-request.dto';
import { ApiResponse, ApiTags } from '@nestjs/swagger';
import { Put } from '@nestjs/common/decorators';
import { PutInvestmentRequestDto } from './dto/req/put-investment-request.dto';

@ApiTags('Investments')
@Controller('investments')
@ApiResponse({
  status: 400,
  description: 'Bad Request',
})
export class InvestmentsController {
  constructor(private readonly investmentsService: InvestmentsService) {}

  @Post()
  @ApiResponse({
    status: 201,
    description: 'The record has been successfully created.',
    type: InvestmentEntity,
  })
  create(@Body() createInvestmentRequestDto: CreateInvestmentRequestDto) {
    return this.investmentsService.create(createInvestmentRequestDto);
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
  update(
    @Param('id') id: string,
    @Body() updateInvestmentDto: UpdateInvestmentRequestDto,
  ) {
    return this.investmentsService.update(+id, updateInvestmentDto);
  }

  @Put(':id')
  withdrawalInvestment(
    @Param('id') id: string,
    @Body() putInvestmentRequestDto: PutInvestmentRequestDto,
  ) {
    return this.investmentsService.withdrawalInvestment(
      +id,
      putInvestmentRequestDto,
    );
  }
}

import { CreateInvestmentResponseDto } from './dto/res/create-investment-response.dto';
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
    type: CreateInvestmentResponseDto,
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

  @Delete(':id')
  withdrawalInvestment(@Param('id') id: string) {
    return this.investmentsService.withdrawalInvestment(+id);
  }
}

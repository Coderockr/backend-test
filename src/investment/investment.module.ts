import { Module } from '@nestjs/common';
import { InvestmentService } from './investment.service';
import { InvestmentController } from './investment.controller';
import { SequelizeModule } from '@nestjs/sequelize';
import { Investment } from './entities/investment.entity';
import DatesHelpers from 'src/helpers/dates-helpers';

@Module({
  imports: [SequelizeModule.forFeature([Investment])],
  controllers: [InvestmentController],
  providers: [InvestmentService, DatesHelpers]
})
export class InvestmentModule {}

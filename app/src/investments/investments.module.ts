import { Module } from '@nestjs/common';
import { InvestmentsService } from './investments.service';
import { InvestmentsController } from './investments.controller';

@Module({
  controllers: [InvestmentsController],
  providers: [InvestmentsService]
})
export class InvestmentsModule {}

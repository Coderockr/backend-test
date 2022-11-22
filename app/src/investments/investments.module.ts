import { InvestimentsRepository } from './repositories/investiments.repository';
import { Module } from '@nestjs/common';
import { InvestmentsService } from './investments.service';
import { InvestmentsController } from './investments.controller';
import { OwnersModule } from 'src/owners/owners.module';

@Module({
  imports: [OwnersModule],
  controllers: [InvestmentsController],
  providers: [InvestmentsService, InvestimentsRepository],
})
export class InvestmentsModule {}

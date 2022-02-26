import { Module } from '@nestjs/common';
import { CommonModule } from './modules/common/common.module';
import { InvestorsModule } from './modules/investors/investors.module';
import { InvestmentsModule } from './modules/investments/investments.module';

@Module({
  imports: [CommonModule, InvestorsModule, InvestmentsModule],
  controllers: [],
  providers: [],
})
export class AppModule {}

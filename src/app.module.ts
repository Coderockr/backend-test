import { Module } from '@nestjs/common';
import { SequelizeModule } from '@nestjs/sequelize';
import { join } from 'path';
import { InvestmentModule } from './investment/investment.module';
import { Investment } from './investment/entities/investment.entity';

@Module({
  imports: [
    SequelizeModule.forRoot({
      dialect: 'sqlite',
      host: join(__dirname, 'database.sqlite'),
      autoLoadModels: true,
      models: [Investment],
    }),
    InvestmentModule,
  ],
  controllers: [],
  providers: [],
})
export class AppModule {}

import { Module } from '@nestjs/common';
import { ConfigModule } from '@nestjs/config';
import { InvestmentsModule } from './investments/investments.module';
import { PrismaModule } from './prisma/prisma.module';
import { OwnersModule } from './owners/owners.module';

@Module({
  imports: [
    InvestmentsModule,
    PrismaModule,
    ConfigModule.forRoot({ isGlobal: true }),
    OwnersModule,
  ],
  controllers: [],
  providers: [],
})
export class AppModule {}

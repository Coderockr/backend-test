import { Module } from '@nestjs/common';
import { ConfigModule } from '@nestjs/config';
import { InvestmentsModule } from './investments/investments.module';
import { PrismaModule } from './prisma/prisma.module';
import { OwnersModule } from './owners/owners.module';
import { ScheduleModule } from '@nestjs/schedule';
import { MailModule } from './mail/mail.module';

@Module({
  imports: [
    ScheduleModule.forRoot(),
    ConfigModule.forRoot({ isGlobal: true }),
    InvestmentsModule,
    PrismaModule,
    OwnersModule,
    MailModule,
  ],
  controllers: [],
  providers: [],
})
export class AppModule {}

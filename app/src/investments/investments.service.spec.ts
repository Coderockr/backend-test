import { PrismaService } from './../prisma/prisma.service';
import { Test, TestingModule } from '@nestjs/testing';
import { InvestmentsService } from './investments.service';
import { OwnersModule } from '../owners/owners.module';
import { MailModule } from '../mail/mail.module';

describe('InvestmentsService', () => {
  let investmentService: InvestmentsService;

  beforeEach(async () => {
    const module: TestingModule = await Test.createTestingModule({
      imports: [OwnersModule, MailModule],
      providers: [InvestmentsService],
    }).compile();

    investmentService = module.get<InvestmentsService>(InvestmentsService);
  });

  it('should be defined', () => {
    expect(investmentService).toBeDefined();
  });
});

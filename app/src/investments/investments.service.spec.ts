import { Test, TestingModule } from '@nestjs/testing';
import { InvestmentsService } from './investments.service';

describe('InvestmentsService', () => {
  let service: InvestmentsService;

  beforeEach(async () => {
    const module: TestingModule = await Test.createTestingModule({
      providers: [InvestmentsService],
    }).compile();

    service = module.get<InvestmentsService>(InvestmentsService);
  });

  it('should be defined', () => {
    expect(service).toBeDefined();
  });
});

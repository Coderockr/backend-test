import { OwnersService } from './../owners/owners.service';
import { Test, TestingModule } from '@nestjs/testing';
import { InvestmentsController } from './investments.controller';
import { InvestmentsService } from './investments.service';

describe('InvestmentsController', () => {
  let investmentController: InvestmentsController;
  let investmentsService: InvestmentsService;

  beforeEach(async () => {
    const module: TestingModule = await Test.createTestingModule({
      controllers: [InvestmentsController],
      providers: [InvestmentsService],
    }).compile();

    investmentController = module.get<InvestmentsController>(
      InvestmentsController,
    );
  });

  it('should be defined', () => {
    expect(investmentController).toBeDefined();
  });
});

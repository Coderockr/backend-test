import { Test, TestingModule } from '@nestjs/testing';
import { InvestmentsController } from '../investments.controller';
import { InvestmentsService } from '../investments.service';
import {
  investment,
  investmentsPaginated,
  investmentWithGains,
  withdrawal,
} from './__mock__/data';
import * as moment from 'moment';

describe('InvestmentsController', () => {
  let investmentsController: InvestmentsController;
  let investmentsService: InvestmentsService;

  beforeEach(async () => {
    const module: TestingModule = await Test.createTestingModule({
      controllers: [InvestmentsController],
      providers: [
        {
          provide: InvestmentsService,
          useValue: {
            create: jest.fn().mockResolvedValue(investment),
            findByInvestor: jest.fn().mockResolvedValue(investmentsPaginated),
            findInvestmentGains: jest
              .fn()
              .mockResolvedValue(investmentWithGains),
            makeWithdrawal: jest.fn().mockResolvedValue(withdrawal),
          },
        },
      ],
    }).compile();

    investmentsController = module.get<InvestmentsController>(
      InvestmentsController,
    );
    investmentsService = module.get<InvestmentsService>(InvestmentsService);
  });

  it('should be defined', () => {
    expect(investmentsController).toBeDefined();
    expect(investmentsService).toBeDefined();
  });

  describe('create', () => {
    it('should return investment created successfully', async () => {
      const result = await investmentsController.create(investment);

      expect(result).toEqual(investment);
      expect(typeof result).toEqual('object');
    });
  });

  describe('findByInvestor', () => {
    it('should return a list paginated of investments', async () => {
      const result = await investmentsController.findByInvestor(
        { investor_name: 'John Snow' },
        {
          page: 1,
          limit: 10,
        },
      );

      expect(result).toEqual(result);
    });
    it('should return a list paginated with two investments', async () => {
      const result = await investmentsController.findByInvestor(
        { investor_name: 'John Snow' },
        {
          page: 1,
          limit: 10,
        },
      );

      expect(result.data).toHaveLength(2);
    });
  });

  describe('findInvestmentGains', () => {
    it('should be return a investment with balance', async () => {
      const result = await investmentsController.findInvestmentGains(1);

      expect(Number(result.expected_balance)).toBeGreaterThanOrEqual(
        Number(result.initial_amount),
      );
    });
  });

  describe('withdrawal', () => {
    it('should be return a withdrawal', async () => {
      const newWithdrawal = {
        investment_id: 1,
        amount: 100.76,
        date: new Date(
          moment().subtract(1, 'days').format(moment.HTML5_FMT.DATE),
        ),
      };
      const result = await investmentsController.makeWithdrawal(newWithdrawal);

      expect(result.date).toEqual(newWithdrawal.date);
    });
  });
});

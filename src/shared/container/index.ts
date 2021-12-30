import { InvestorRepository } from 'src/modules/investors/infra/prisma/repositories/InvestorRepository';
import { IInvestorRepository } from 'src/modules/investors/repositories/IInvestorRepository';
import { container } from 'tsyringe';

import './providers';

container.registerSingleton<IInvestorRepository>(
  'InvestorRepository',
  InvestorRepository,
);


import { container } from 'tsyringe';

import './providers';

import { IInvestmentsRepository } from '@modules/investments/repositories/IInvestmentsRepository';
import { InvestmentsRepository } from '@modules/investments/infra/prisma/InvestmentsRepository';
import { IInvestorRepository } from '@modules/investors/repositories/IInvestorRepository';
import { InvestorRepository } from '@modules/investors/infra/prisma/repositories/InvestorRepository';

container.registerSingleton<IInvestorRepository>(
  'InvestorRepository',
  InvestorRepository,
);

container.registerSingleton<IInvestmentsRepository>(
  'InvestmentsRepository',
  InvestmentsRepository,
);


import { container } from 'tsyringe';

import { DayjsDateProvider } from './DateProvider/implementations/DayjsDateProvider';
import { CalculateGainProvider } from './CalculateGainProvider/implementations/CalculateGainProvider';

container.registerSingleton(
  'DayjsDateProvider',
  DayjsDateProvider,
);
container.registerSingleton(
  'CalculateGainProvider',
  CalculateGainProvider,
);


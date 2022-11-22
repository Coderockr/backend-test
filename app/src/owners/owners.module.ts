import { Module } from '@nestjs/common';
import { OwnersService } from './owners.service';
import { OwnersController } from './owners.controller';
import { OwnersRepository } from './repositories/owners.repository';

@Module({
  controllers: [OwnersController],
  providers: [OwnersService, OwnersRepository],
  exports: [OwnersService],
})
export class OwnersModule {}

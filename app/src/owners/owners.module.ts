import { Module } from '@nestjs/common';
import { OwnersService } from './owners.service';
import { OwnersController } from './owners.controller';
import { OwnerRepository } from './repositories/owners.repository';

@Module({
  controllers: [OwnersController],
  providers: [OwnersService, OwnerRepository],
})
export class OwnersModule {}

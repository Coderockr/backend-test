import { Module } from '@nestjs/common';
import { TypeOrmModule } from '@nestjs/typeorm';
import typeormConfig from './config/typeorm.config';

@Module({
  imports: [TypeOrmModule.forRoot(typeormConfig.ormConfig())],
})
export class CommonModule {}

import { TypeOrmModuleOptions } from '@nestjs/typeorm';
import * as _ from 'lodash';
import { BaseConfig } from './base.config';

export class TypeormConfig extends BaseConfig {
  ormConfig(): TypeOrmModuleOptions {
    return {
      type: 'mysql',
      url: this.getValue('TYPEORM_URL'),
      entities: [this.getValue('TYPEORM_ENTITIES')],
      migrations: [this.getValue('TYPEORM_MIGRATIONS')],
      logging: !this.isProduction(),
      autoLoadEntities: true,
      migrationsRun: _.isEqual(
        this.getValue('TYPEORM_MIGRATIONS_RUN') || 'true',
        'true',
      ),
      synchronize: _.isEqual(
        this.getValue('TYPEORM_SYNCRONIZE') || false,
        'true',
      ),
      cli: {
        migrationsDir: this.getValue('TYPEORM_MIGRATIONS_DIR'),
      },
    };
  }
}

export default new TypeormConfig(process.env);

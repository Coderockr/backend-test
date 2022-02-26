import { NotImplementedException } from '@nestjs/common';
import * as _ from 'lodash';
import { EnvAccessor } from '../interfaces/env-acessor';

export class BaseConfig implements EnvAccessor {
  constructor(private readonly env: Record<string, string>) {}
  /**
   * Obtêm uma variável de ambiente
   *
   * @returns
   */
  getValue(key: string, defaultValue = '') {
    return this.env[key] ?? defaultValue;
  }

  /**
   * Obtêm uma variável de ambiente ou
   * falha caso a mesma não exista
   *
   * @returns
   */
  getValueOrFail(key: string) {
    const value = this.getValue(key);

    if (_.isEmpty(value)) {
      throw new NotImplementedException(
        ` A variável de ambiente ${key} não está definida `,
      );
    }

    return value;
  }

  isProduction() {
    return _.isEqual(this.getValue('APP_ENV'), 'production');
  }
}

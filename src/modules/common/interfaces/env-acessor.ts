export interface EnvAccessor {
  getValue(key: string, defaultValue: string): string;
  getValueOrFail(key: string): string;
}

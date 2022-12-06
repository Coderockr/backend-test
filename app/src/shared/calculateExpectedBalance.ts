import { GainTax } from './gain';

export function calculateBalance(amount) {
  return amount + amount * GainTax.VALUE;
}

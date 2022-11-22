import { GainTax } from './gain';

export function calculateBalance(amount: number) {
  const gain = amount * GainTax.VALUE;
  const sum = gain + amount;

  return sum;
}

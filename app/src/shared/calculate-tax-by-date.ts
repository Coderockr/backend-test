import { calculateDayDiff } from './calculate-day-diff';

export function calculateTaxByDate(
  amount,
  amountGained,
  creationDate,
  withdrawalDate,
) {
  const startDate = +new Date(creationDate);
  const endDate = +new Date(withdrawalDate);

  const diffDays = calculateDayDiff(startDate, endDate);
  console.log(diffDays);
  const taxValue = getTaxValue(diffDays) * amountGained;
  const amountCalculated = amount - taxValue;

  return amountCalculated;
}

function getTaxValue(diffDays: number) {
  let taxValue;
  if (diffDays <= 365) {
    taxValue = 0.225;
  } else if (diffDays > 365 && diffDays <= 730) {
    taxValue = 0.185;
  } else {
    taxValue = 0.15;
  }
  return taxValue;
}

// If it is less than one year old, the percentage will be 22.5% (tax = 45.00).
//If it is between one and two years old, the percentage will be 18.5% (tax = 37.00).
//If older than two years, the percentage will be 15% (tax = 30.00).

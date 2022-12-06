export function calculateTaxByDate(amount, creationDate) {
  const startDate = +new Date(creationDate);
  const endDate = +new Date();

  const diffDays = (endDate - startDate) / (1000 * 60 * 60 * 24);

  const calculatedAmount = 1;
  return calculatedAmount;
}

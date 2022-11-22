export function calculateDayDiff(startDate, endDate) {
  return ((endDate - startDate) / (1000 * 60 * 60 * 24)) | 0;
}

export function formatDate(date: Date) {
  return date.toISOString().split('T')[0];
}

import { Investment } from "../InvestmentModel"

const getMonthsPassed = async (initialDate) => {
  const startDate = new Date(initialDate);
  const currentDate = new Date();
  const monthDifference =
    (currentDate.getFullYear() - startDate.getFullYear()) * 12 +
    (currentDate.getMonth() - startDate.getMonth());
  return monthDifference;
};

export const getGain = async (investment: Investment) => {

  const monthsSinceCreation = await getMonthsPassed(`${investment.createdAt}`)
  const monthsSinceUpdate = await getMonthsPassed(`${investment.updatedAt}`)
  return {
    create: monthsSinceCreation,
    update: monthsSinceUpdate
  }
}

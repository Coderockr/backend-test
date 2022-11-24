import { createLoader } from "@entria/graphql-mongo-helpers";
import { registerLoader } from "../loader/loaderRegister";
import InvestmentModel from "./InvestmentModel";

const {
  Wrapper: Investment,
  getLoader,
  clearCache,
  load,
  loadAll,
} = createLoader({
  model: InvestmentModel,
  loaderName: 'InvestmentLoader'
})

export { getLoader, clearCache, load, loadAll }
export default Investment

registerLoader('InvestmentLoader', getLoader)

import { Router } from "express";
import { ensureAuthenticate } from "./middlewares/ensureAuthenticate";
import { CreateInvestorController } from "./modules/investors/useCases/createInvestor/CreateInvestorController";
import { AuthenticateController } from "./modules/account/useCases/authenticate/AuthenticateController";
import { CreateInvestmentController } from "./modules/investments/useCases/createInvestment/CreateInvestmentController";
import { FindAllInvestmentsController } from "./modules/investments/useCases/findAllInvestments/FindAllInvestmentsController";
import { WithdrawnInvestmentController } from "@modules/investments/useCases/withdrawnInvestment/useCases/WithdrawnInvestmentController";

const routes = Router();

const createInvestorController = new CreateInvestorController();
const authenticateController = new AuthenticateController();
const createInvestmentController = new CreateInvestmentController();
const findAllInvestmentsController = new FindAllInvestmentsController();
const withdrawnInvestmentController = new WithdrawnInvestmentController();

routes.post("/investor", createInvestorController.handle);
routes.post("/authenticate", authenticateController.handle);
routes.post("/investment", ensureAuthenticate, createInvestmentController.handle);
routes.get("/investment/:page?", ensureAuthenticate, findAllInvestmentsController.handle);
routes.put("/withdrawn/:id", ensureAuthenticate, withdrawnInvestmentController.handle);

export { routes };

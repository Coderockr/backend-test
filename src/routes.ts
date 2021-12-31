import { Router } from "express";
//import { CreateClientController } from "./modules/clients/useCases/createClient/CreteClientController";
//import { CreateDeliverymanController } from "./modules/deliveryman/useCases/createDeliveryman/CreateDeliverymanController";
//import { AuthenticateDeliverymanController } from "./modules/account/authenticateDeliveryman/AuthenticateDeliverymanController";
//import { CreateDeliveryController } from "./modules/deliveries/useCases/createDelivery/CreateDeliveryController";
import { ensureAuthenticate } from "./middlewares/ensureAuthenticate";
//import { FindAllAvailableController } from "./modules/deliveries/useCases/findAllAvailable/FindAllAvailableController";
//import { ensureAuthenticateDeliveryman } from "./middlewares/ensureAuthenticateDeliveryman";
//import { UpdateDeliverymanController } from "./modules/deliveries/useCases/updateDeliveryman/useCases/UpdateDeliverymanController";
//import { FindAllDeliveriesController } from "./modules/clients/useCases/deliveries/FindAllDeliveriesController";
//import { FindAllDeliveriesDeliverymanController } from "./modules/deliveryman/useCases/findAllDeliveries/FindAllDeliveriesDeliverymanController";
//import { UpdateEndDateController } from "./modules/deliveries/useCases/updateEndDate/UpdateEndDateController";
import { CreateInvestorController } from "./modules/investors/useCases/createInvestor/CreateInvestorController";
import { AuthenticateController } from "./modules/account/useCases/authenticate/AuthenticateController";
import { CreateInvestmentController } from "./modules/investments/useCases/createInvestment/CreateInvestmentController";
import { FindAllInvestmentsController } from "./modules/investments/useCases/findAllInvestments/FindAllInvestmentsController";
import { WithdrawnInvestmentController } from "@modules/investments/useCases/withdrawnInvestment/useCases/WithdrawnInvestmentController";



const routes = Router();

/*
const createClientController = new CreateClientController();

const createDeliverymanController = new CreateDeliverymanController();
const authenticateDeliverymanController =
  new AuthenticateDeliverymanController();

const deliveryController = new CreateDeliveryController();
const findAllAvailableController = new FindAllAvailableController();
const updateDeliverymanController = new UpdateDeliverymanController();
const findAllDeliveriesClient = new FindAllDeliveriesController();

const findAllDeliveriesDeliveryman =
  new FindAllDeliveriesDeliverymanController();

const updateEndDateController = new UpdateEndDateController();

const createInvestorController = new CreateInvestorController();


routes.post(
  "/deliveryman/authenticate",
  authenticateDeliverymanController.handle
);
*/

const createInvestorController = new CreateInvestorController();
const authenticateController = new AuthenticateController();
const createInvestmentController = new CreateInvestmentController();
const findAllInvestmentsController = new FindAllInvestmentsController();
const withdrawnInvestmentController = new WithdrawnInvestmentController();

////////////////////////////////
routes.post("/investor", createInvestorController.handle);

routes.post("/authenticate", authenticateController.handle);

routes.post("/investment", ensureAuthenticate, createInvestmentController.handle);
routes.get("/investment/:page?", ensureAuthenticate, findAllInvestmentsController.handle);
routes.put("/withdrawn/:id", ensureAuthenticate, withdrawnInvestmentController.handle);




////////////////////////////////
/*
routes.post("/deliveryman", createDeliverymanController.handle);


routes.get(
  "/delivery/available",
  ensureAuthenticateDeliveryman,
  findAllAvailableController.handle
);

routes.put(
  "/delivery/updateDeliveryman/:id",
  ensureAuthenticateDeliveryman,
  updateDeliverymanController.handle
);

routes.get(
  "/client/deliveries",
  ensureAuthenticateClient,
  findAllDeliveriesClient.handle
);

routes.get(
  "/deliveryman/deliveries",
  ensureAuthenticateDeliveryman,
  findAllDeliveriesDeliveryman.handle
);

routes.put(
  "/delivery/updateEndDate/:id",
  ensureAuthenticateDeliveryman,
  updateEndDateController.handle
);
*/

export { routes };

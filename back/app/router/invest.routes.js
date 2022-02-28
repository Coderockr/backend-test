module.exports = app => {
    const invest = require("../controllers/invest.controller.js");

    var router = require("express").Router();
  
    router.post("/", invest.create);
  
    router.get("/list", invest.findAll);
  
    router.post("/withdrawal", invest.withdrawal);

    router.get("/:id", invest.findOne);
  
    app.use("/api/invest", router);
    

    const owner = require("../controllers/owner.controller.js");

    var router2 = require("express").Router();
  
    router2.post("/", owner.create);
  
    router2.get("/list", owner.findAll);
  
    router2.get("/:id", owner.findOne);
  
    app.use("/api/owner", router2);


  };
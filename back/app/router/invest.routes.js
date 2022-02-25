module.exports = app => {
    const invest = require("../controllers/invest.controller.js");

    var router = require("express").Router();
  
    router.post("/", invest.create);
  
    router.get("/list", invest.findAll);
  
    router.post("/withdrawal", invest.withdrawal);

    router.get("/:id", invest.findOne);
  
    app.use("/api/invest", router);
  };
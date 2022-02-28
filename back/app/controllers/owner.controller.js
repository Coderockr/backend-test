const db = require("../models");
const Owner = db.owner;

exports.create = (req,res) => {
    if (!req.body.owner_name ) {
        res.status(400).send({ message: "Content can not be empty!" });
        return;
      }

      const owner = new Owner({
          owner_name: req.body.owner_name
        });
        
      owner
        .save(owner)
        .then(data => {
            res.status(201).send(data);
        })
        .catch(err => {
          res.status(500).send({
            message:
              err.message || "Some error occurred while creating."
          });
        });
}
exports.findAll = (req,res) => {

    Owner.find()
    .then(data => {
        res.send(data);
    })
    .catch(err => {
        console.log(err)
      res.status(500).send({
        message:
          err.message || "Some error occurred while retrieving."
      });
    });
}
exports.findOne = (req,res) => {

}
const db = require("../models");
const Invest = db.invest;

exports.create = (req,res) => {
      if(!req.body.owner ) {
        res.status(400).send({ message: "Content can not be empty!" });
        return;
      }
      if(req.body.valueInitalInvest < 0 ) {
        res.status(400).send({ message: "The initial value of investiment can't be negative!" });
        return;
      }
       
        dataAtual = new Date(new Date().getTime());
     
      if(new Date(req.body.dateInvest) > dataAtual)
      {
        res.status(400).send({ message: "Can't set future date of investiment!"});
        return;
      }

      const invest = new Invest({
          owner: req.body.owner,
          valueInitalInvest: req.body.valueInitalInvest,
          dateInvest: new Date(req.body.dateInvest),
          withdrawn:req.body.withdrawn,
          valueWithdrawn:0,
          dateWithdrawn:null
        });
      invest
        .save(invest)
        .then(data => {
            res.status(201).send(data);
        })
        .catch(err => {
            console.log("error"+err)
          res.status(500).send({
            message:
              err.message || "Some error occurred while creating."
          });
        });
}
exports.findAll = (req,res) => {
    owner = req.query.owner;
    pg = req.query.pg;

    var end = 5;
    var init = pg ? ((pg-1)*5) : 0;
    var condition = owner ? { owner: { $eq: owner} } : {};

    Invest.find(condition).limit(end).skip(init)
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
exports.withdrawal = (req,res) => {
    var idInvest = req.body.id_invest;
    var dataWithdrawn = new Date(req.body.dataWithdrawn.substr(0,10));
    var dateInvest = req.body.dateInvest
    var valueWithdrawn = req.body.valueWithdrawn;

    var d = new Date();
    d.setHours(0,0,0,0);
    var di = new Date(dateInvest)
    di.setHours(0,0,0,0);

    var dataAtual = new Date(d.getTime() + (3600000*(-3)))
    var dataI = new Date(di.getTime() + (3600000*(-3)))

    if( dataWithdrawn > dataAtual || dataWithdrawn < dataI){
        res.status(400).send({ message: "Data de retirada do investimento inválida"});
    }
    Invest.updateOne(
    {_id:idInvest},
    { $set: {
        withdrawn: true,
        valueWithdrawn:valueWithdrawn,
        dateWithdrawn:dataWithdrawn
      }
    }).then((data)=>{
        res.status(201).send({data});
    })
   
}
exports.findOne = (req,res) => {
    id = req.params.id
    dataWithdrawn = req.query.dataWithdrawn

    Invest.findById(id)
    .then(data => {
      if (!data)
        res.status(404).send({ message: "Not found Investiment with id " + id });
        else {
          var d = new Date(data.dateInvest)
    	  d.setHours(0,0,0,0);

          data2 = dataWithdrawn ? new Date(dataWithdrawn.substr(0,30)) : new Date((new Date().getTime() + (3600000*(-3)))) // data com fuso horario corrigido

	  var di = new Date();
          di.setHours(0,0,0,0);

          data1 = new Date(d.getTime() + (3600000*(-3)))
    	  now = new Date(di.getTime() + (3600000*(-3)))          
          if( data2 > now || data2 < data1){
            res.status(400).send({ message: "Data de retirada do investimento inválida! Obs: a data deve ser maior que a data de lançamento e menor ou igual ao dia de hoje."});
            return;
          }
          var total = (data2.getFullYear() - data1.getFullYear())*12 + (data2.getMonth() - data1.getMonth());
          if((data2.getDate() - data1.getDate()) < 0 ){
              total--;
          }
          
          let result = data.valueInitalInvest;
          for(i=0;i<total;i++){
             result += (result * 0.0052);
          }

          lucroBruto = result - data.valueInitalInvest;
          lucro = result - data.valueInitalInvest;
          let imposto;
            if(total < 12){
                lucro -= (lucro * 0.225);
                imposto = "22,5%"
              }
              if(total >= 12 && total <= 24){
                lucro -= (lucro * 0.185);
                imposto = "18,5%"
              }
              if(total > 24){
                lucro -=  (lucro * 0.15);
                imposto = "15%"
            }


          res.status(201).send({lucroBruto:lucroBruto.toFixed(2),imposto:imposto,total:total,result:parseFloat((data.valueInitalInvest + lucro).toFixed(2))});
      }
    })
    .catch(err => {
        console.log("error"+err)
      res
        .status(500)
        .send({ message: "Error retrieving Investiment with id=" + id });
    });

}
import { Investment } from "./Investment";
const express = require("express");
const res = require("express/lib/response");
const app = express();

app.use(express.json())





app.post("/createinvestment", (req, res) => {
    const investiment = new Investment(
        req.body.owner,
        req.body.creationDate,
        req.body.amount)
    res.send(investiment)


})

app.get("/documentation", (req, res) => {
    res.send("Here are the docs!")
})

app.listen(3000, () => {
    console.log("Server is Running!")
})
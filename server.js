const express = require("express");
const app = express();
const { Owner, Investment } = require("./Classes");
app.use(express.json())


app.post("/createinvestment", (req, res) => {
    const investiment = new Investment(
        req.body.owner,
        req.body.creationDate,
        req.body.amount)

    res.send(investiment)


})

app.post("/createowner", (req, res) => {
    const owner = new Owner(
        req.body.firstName,
        req.body.lastName,
        req.body.email,
        req.body.phoneNumber)

    res.send(investiment)


})

app.get("/documentation", (req, res) => {
    res.send("Here are the docs!")
})

app.listen(3000, () => {
    console.log("Server is Running!")
})
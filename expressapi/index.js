const express = require("express");
const app = express();
const { controllerInvestment } = require("./src/Controllers/investment");
const { controllerOwner } = require("./src/Controllers/owner");
const { controllerWithdraw } = require("./src/Controllers/withdraw");

app.use(express.json());
app.use((req, res, next) => {
    res.append('Access-Control-Allow-Origin', ['*']);
    res.append('Access-Control-Allow-Methods', 'GET,PUT,POST,DELETE');
    res.append('Access-Control-Allow-Headers', 'Content-Type');
    next();
});

app.all("/investment", (req, res) => {
    let lastResponse = controllerInvestment(req);
    res.status(lastResponse.status);
    res.send(lastResponse);
});

app.all("/owner", (req, res) => {
    let lastResponse = controllerOwner(req);
    res.status(lastResponse.status);
    res.send(lastResponse);
});

app.all("/withdraw", (req, res) => {
    let lastResponse = controllerWithdraw(req);
    res.status(lastResponse.status);
    res.send(lastResponse);
});

app.get("/documentation", (req, res) => {
    res.send("Here are the docs!");
    /**It's a Lie, lets do this docs! */
});

app.listen(3030, () => {
    console.log("Server is Running!");
});
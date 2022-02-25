const express = require("express");
const app = express();
const { Investment } = require("./Investment")
const { Owner } = require("./Owner");
app.use(express.json())
let owners = []
let investments = []


/**Adjust problems with the date format (2022-02-24T08:12:56.671Z) */
app.post("/createinvestment", (req, res) => {
    const ownerId = req.body.ownerId
    if (owners.filter(owner => owner.ownerId == ownerId).length == 1) {
        const date = new Date(req.body.creationDate)
        const investment = new Investment(
            ownerId, date,
            req.body.amount)
        if (investment.error) {
            res.status(406)
                /**How is the best way to show this response? */
            res.send(investment.errorList)
        } else {
            res.status(201)
            investments.push(investment)
        }
        /**Temporary to keep investments */
    } else {
        res.status(400)
            /**How is the best way to show this response? */
        res.send("14 - Invalid userId for set an investment.")
    }
    res.send(investments)
})


app.post("/createowner", (req, res) => {
    const owner = new Owner(
            req.body.firstName,
            req.body.lastName,
            req.body.email,
            req.body.phoneNumber)
        /**Check for unique identifiers */
    if (owner.error) {
        res.status(406)
            /**How is the best way to show this response? */
        res.send(owner.errorList)

    } else {
        res.status(201)
            /**Temporary to keep owners */
        owners.push(owner)
    }

    res.send(owners)
})


app.get("/owner/:id", (req, res) => {
    /**Lead with 0 owners and filter by id*/
    res.send(owners[req.params.id - 1])
})



app.get("/documentation", (req, res) => {
    res.send("Here are the docs!")
})

app.listen(3000, () => {
    console.log("Server is Running!")
})
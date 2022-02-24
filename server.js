const express = require("express");
const app = express();
const { Owner, Investment } = require("./Classes");
app.use(express.json())
let owners = []


/**Adjust problems with the date format (2022-02-24T08:12:56.671Z) */
app.post("/createinvestment", (req, res) => {
    const owner = owners[req.body.idOwner - 1]
    date = new Date(Date.now())
    const investment = new Investment(
        owner, date,
        req.body.amount)
    res.send(investment)
})




app.post("/createowner", (req, res) => {
    const owner = new Owner(
            req.body.firstName,
            req.body.lastName,
            req.body.email,
            req.body.phoneNumber)
        /**Temporary to keep owners */
    owners.push(owner)

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
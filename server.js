const express = require("express");
const app = express();
const { Investment } = require("./src/models/Investment")
const { Owner } = require("./src/models/Owner");
const { Response } = require("./src/models/Response")

app.use(express.json())
let owners = []
let investments = []


/**; or not ; that's the question :)*/
/**Adjust problems with the date format (2022-02-24T08:12:56.671Z) */

app.post("/investment", (req, res) => {
    let lastResponse = new Response();
    let ownerId = req.body.ownerId
    ownerId == null ? ownerId = 0 : null
    if (owners.filter(owner => owner.ownerId == ownerId).length == 1) {

        const date = new Date(req.body.creationDate)
        const investment = new Investment(
            ownerId, date,
            req.body.amount)

        if (investment.error) {
            lastResponse.setError(406, "Class Level Error", investment.errorList)
        } else {
            investments.push(investment)
            lastResponse.setSuccess(201, "Investment Sucessfuly Created", investment)
        }
    } else {
        lastResponse.setError(400, "Request Level Error", "14 - Invalid userId for set an investment.")
    }
    /**Lugar certo para response */
    res.send(lastResponse)


})

app.post("/owner", (req, res) => {
    let lastResponse = new Response();
    const owner = new Owner(
            req.body.firstName,
            req.body.lastName,
            req.body.email,
            req.body.phoneNumber)
        /**Check for unique identifiers */
    if (owner.error) {
        lastResponse.setError(406, "Class Level Error", owner.errorList)
    } else {
        owners.push(owner)
        lastResponse.setSuccess(201, "Owner Sucessfylly Created", owner)
    }
    res.send(lastResponse)
})

app.get("/withdraw", (req, res) => {
    let lastResponse = new Response();
    const dateNow = new Date(Date.now())
    const index = investments.findIndex(i => i.investmentId === req.body.investmentId);
    if (index < 0) {
        lastResponse.setError(406, "Request Level Error", "16 - Investment not found.")
    } else {
        const date = new Date(Date.parse(req.body.date))
        if (date.getTime() > dateNow.getTime() || !(date instanceof Date) || isNaN(date)) {
            lastResponse.setError(400, "Request Level Error", "17 - Invalid date to Withdraw.")
        } else {
            const sacado = investments[index].withdraw(date);
            if (investments[index].error) {
                lastResponse.setError(400, "Class Level Error", investments[index].errorList);
            } else {
                lastResponse.setSuccess(200, `Withdrawn value: USD${sacado}`, investments[index])
            }
        }
    }
    res.send(lastResponse)
})

app.get("/investment", (req, res) => {
    /**What about return all investments? */
    let lastResponse = new Response();
    if (req.query.id != null) {
        const index = investments.findIndex(i => i.investmentId == req.query.id);
        if (index < 0) {
            lastResponse.setError(406, "Request Level Error", "18 - Investment not found.")
        } else {
            lastResponse.setSuccess(200, "Investment Sucessfully Loaded", investments[index])
        }
    } else {
        if (req.query.page != null) {
            const page = parseInt(req.query.page)
            if (page > 0) {
                const ownerInvestments = investments.filter(investment => investment.ownerId === req.body.ownerId)
                const firstItem = (req.query.page * 10) - 9
                if (ownerInvestments.length > firstItem + 9 || ownerInvestments.length > firstItem) {
                    const lastItem = firstItem + 9 > ownerInvestments.length ? ownerInvestments.length : firstItem + 9

                    lastResponse.setSuccess(200, `Investments Page Sucessfully Loaded`, ownerInvestments.slice(firstItem - 1, lastItem))
                } else {
                    lastResponse.setError(406, "Request Level Error", "19 - Out of Investment Bounds.")
                }
            } else {
                lastResponse.setError(406, "Request Level Error", "20 - Page must be greater than zero.")
            }
        }
    }
    res.send(lastResponse)
})


app.get("/owner", (req, res) => {

    let lastResponse = new Response();
    /**Lead with 0 owners and filter by id*/
    if (req.query.id != null) {
        if (req.query.id > owners.length) {
            lastResponse.setError(406, "Request Level Error", "22- Owner array out of bound.")
        } else {
            lastResponse.setSuccess(200, "Owner Sucessfully Loaded", owners[req.query.id - 1])
        }
    } else {
        if (req.query.page != null) {
            const page = parseInt(req.query.page)
            if (page > 0) {
                const firstItem = (req.query.page * 10) - 9
                if (owners.length > firstItem + 9 || owners.length > firstItem) {
                    const lastItem = firstItem + 9 > owners.length ? owners.length : firstItem + 9

                    lastResponse.setSuccess(200, `Owners Page Sucessfully Loaded`, owners.slice(firstItem - 1, lastItem))
                } else {
                    lastResponse.setError(406, "Request Level Error", "23 - Out of Owners arrat Bounds.")
                }
            } else {
                lastResponse.setError(406, "Request Level Error", "24 - Page must be greater than zero.")
            }
        } else {
            lastResponse.setSuccess(200, "All Owners Page Sucessfully Loaded", owners)
        }
        /**Paginate this */
    }
    res.send(lastResponse)
})



app.get("/documentation", (req, res) => {
    res.send("Here are the docs!")
})

app.listen(3000, () => {
    console.log("Server is Running!")
})
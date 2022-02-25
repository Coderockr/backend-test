const { Data } = require("../../Controllers/data")
const { Investment } = require("../../models/Investment")
const { Response } = require("../../models/Response")

function controllerInvestment(req) {
    let lastResponse = new Response();
    if (req.method == "POST") {
        let ownerId = req.body.ownerId;
        ownerId == null ? ownerId = 1 : null;

        if (Data.owners.filter(owner => owner.ownerId == ownerId).length == 1) {
            const date = new Date(req.body.creationDate);
            const investment = new Investment(
                ownerId, date,
                req.body.amount)
            if (investment.error) {
                lastResponse.setError(406, "Class Level Error", investment.errorList);
            } else {
                Data.investments.push(investment);
                lastResponse.setSuccess(201, "Investment Sucessfuly Created", investment);
            }
        } else {
            lastResponse.setError(400, "Request Level Error", "13 - Invalid userId for set an investment.");
        }
        return lastResponse;
    } else {
        if (req.method == "GET") {
            if (req.query.id != null) {
                const index = Data.investments.findIndex(i => i.investmentId == req.query.id);
                if (index < 0) {
                    lastResponse.setError(406, "Request Level Error", "15 - Investment not found.");
                } else {
                    lastResponse.setSuccess(200, "Investment Sucessfully Loaded", Data.investments[index]);
                }
            } else {
                if (req.query.page != null) {
                    const page = parseInt(req.query.page);
                    if (page > 0) {
                        const ownerInvestments = Data.investments.filter(investment => investment.ownerId === req.body.ownerId);
                        const firstItem = (req.query.page * 10) - 10;
                        if (ownerInvestments.length > firstItem + 10 || ownerInvestments.length > firstItem) {
                            const lastItem = firstItem + 10 > ownerInvestments.length ? ownerInvestments.length : firstItem + 10;
                            lastResponse.setSuccess(200, `Investments Page Sucessfully Loaded`, ownerInvestments.slice(firstItem, lastItem));
                        } else {
                            lastResponse.setError(406, "Request Level Error", "19 - Out of Investment Bounds.");
                        }
                    } else {
                        lastResponse.setError(406, "Request Level Error", "20 - Page must be greater than zero.");
                    }
                }
            }
            return lastResponse;
        } else {
            /**Invalid method */
        }
    }
}

module.exports = { controllerInvestment };
const { Data } = require("../../../Controllers/data");
const { Investment } = require("../../../models/Investment");
const { Response } = require("../../../models/Response");

function post(req) {
    let lastResponse = new Response();
    let ownerId = req.body.ownerId;
    ownerId == null ? ownerId = 1 : null;

    if (Data.owners.filter(owner => owner.ownerId == ownerId).length == 1) {
        const date = new Date(req.body.creationDate);
        const investment = new Investment(
            ownerId, date,
            req.body.amount);
        if (investment.error) {
            lastResponse.setError(406, "Class Level Error", investment.errorList);
        } else {
            Data.investments.push(investment);
            lastResponse.setSuccess(201, "Investment Sucessfuly Created", investment);
        };
    } else {
        lastResponse.setError(400, "Request Level Error", "13 - Invalid userId for set an investment.");
    };
    return lastResponse;
};

module.exports = { post };
const { Data } = require("../../data");
const { Response } = require("../../../models/Response");
const { validator } = require("../../validator");
const { paginator } = require("../../paginator");

function get(req) {
    let lastResponse = new Response();
    let investmentArray = [];

    let investmentId = req.query.id;
    let page = req.query.page;
    const ownerId = req.body.ownerId;
    if (Data.investments.length > 0) {

        if (validator("any", investmentId)) {
            page = ""
            if (validator("number", investmentId)) {
                investmentId = parseInt(investmentId)
                const index = Data.investments.findIndex(i => i.investmentId == investmentId);

                if (index >= 0) {
                    investmentArray.push(Data.investments[index])
                    lastResponse.setSuccess(200, "Investment sucessfully loaded", Data.investments[index]);
                } else {
                    lastResponse.setError(406, "Request level error", "14 - Investment not found.");
                };
            } else {
                lastResponse.setError(406, "Request level error", "15 - Invalid investment id.");
            };
        } else {
            if (validator("any", ownerId)) {
                if (validator("number", ownerId)) {
                    const ownerInvestments = Data.investments.filter(investment => investment.ownerId == ownerId);
                    if (ownerInvestments.length > 0) {
                        investmentArray.push(...ownerInvestments)
                        lastResponse.setSuccess(200, "Owner investments sucessfully loaded", );
                    } else {
                        lastResponse.setError(406, "Request level error", "16 - No investments for this Owner.");
                    }
                } else {
                    lastResponse.setError(406, "Request level error", "17 - Invalid owner id.");
                };
            } else {
                investmentArray.push(...Data.investments)
                lastResponse.setSuccess(200, "All investments sucessfully loaded", );
            }
        }

        if (validator("any", page)) {
            if (validator("number", page)) {
                page = parseInt(page)

                let message = ""
                if (lastResponse.message == "Owner investments sucessfully loaded") {
                    message = "Owner investment"
                } else {
                    message = "Investment"
                }
                return paginator(page, investmentArray, message)

            } else {
                lastResponse.setError(406, "Request level error", "18 - Invalid page number.");
            };
        } else {
            lastResponse.objects = []
            lastResponse.objects.push(...investmentArray);
        }
    } else {
        lastResponse.setError(406, "Request level error", "19 - No investments found.");
    };

    return lastResponse;
};

module.exports = {get };
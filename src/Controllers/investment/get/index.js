const { Data } = require("../../../Controllers/data");
const { Response } = require("../../../models/Response");

function get(req) {
    let lastResponse = new Response();
    if (req.query.id != null) {
        const index = Data.investments.findIndex(i => i.investmentId == req.query.id);
        if (index < 0) {
            lastResponse.setError(406, "Request Level Error", "15 - Investment not found.");
        } else {
            lastResponse.setSuccess(200, "Investment Sucessfully Loaded", Data.investments[index]);
        };
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
                };
            } else {
                lastResponse.setError(406, "Request Level Error", "20 - Page must be greater than zero.");
            };
        };
    };
    return lastResponse;
};

module.exports = {get };
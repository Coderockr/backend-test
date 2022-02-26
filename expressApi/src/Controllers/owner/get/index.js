const { Response } = require("../../../models/Response");
const { validator } = require("../../../Controllers/validator");
const { paginator } = require("../../../Controllers/paginator");
const { Data } = require("../../data");

function get(req) {
    let lastResponse = new Response();
    const ownerId = req.query.id;
    const page = req.query.page;

    if (Data.owners.length > 0) {
        if (validator("any", ownerId)) {
            if (validator("number", ownerId)) {
                const owner = Data.owners.filter(owner => owner.ownerId == ownerId);
                if (owner.length > 0) {
                    lastResponse.setSuccess(200, "Owner sucessfully loaded", owner);

                } else {
                    lastResponse.setError(406, "Request level error", "21 - Owner not found.");
                }

            } else {
                lastResponse.setError(406, "Request level error", "22 - Invalid owner id.");
            };

        } else {
            if (validator("number", page)) {
                return paginator(page, Data.owners, "Owners");
            } else {
                lastResponse.setSuccess(200, "All owners sucessfully loaded", Data.owners);
            };

        };
    } else {
        lastResponse.setError(406, "Request level error", "23 - No owners found.");
    }
    return lastResponse;

};
module.exports = {get };
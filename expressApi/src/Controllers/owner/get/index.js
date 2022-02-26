const { Response } = require("../../../models/Response");
const { validator } = require("../../../Controllers/validator");
const { paginator } = require("../../../Controllers/paginator");
const { Data } = require("../../data");

function get(req) {
    let lastResponse = new Response();
    const ownerId = req.query.id
    const page = req.query.page

    /**tem owners? */
    if (Data.owners.length > 0) {
        if (validator("any", ownerId)) {
            if (validator("number", ownerId)) {
                const owner = Data.owners.filter(owner => owner.ownerId == ownerId);
                if (owner.length > 0) {
                    lastResponse.setSuccess(200, "Owner sucessfully loaded", owner);

                } else {
                    lastResponse.setError(406, "Request level error", "25 - Owner not found.");
                }

            } else {
                lastResponse.setError(406, "Request level error", "24 - Invalid owner id.");
            };

        } else {
            /**Tem page? */
            if (validator("number", page)) {
                return paginator(page, Data.owners, "Owners")
            } else {
                lastResponse.setSuccess(200, "All owners sucessfully loaded", Data.owners);
            };

        };
    } else {
        lastResponse.setError(406, "Request level error", "23- No owners found.");
    }


    if (req.query.id != null) {
        if (req.query.id > Data.owners.length || req.query.id < 0) {
            lastResponse.setError(406, "Request level error", "21- Owner not Found.");
        } else {
            lastResponse.setSuccess(200, "Owner sucessfully loaded", Data.owners[req.query.id - 1]);
        };
    } else {
        if (req.query.page != null) {



            const page = parseInt(req.query.page);
            if (page > 0) {
                const firstItem = (req.query.page * 10) - 9;
                if (Data.owners.length > firstItem + 9 || Data.owners.length > firstItem) {
                    const lastItem = firstItem + 9 > Data.owners.length ? Data.owners.length : firstItem + 9;
                    lastResponse.setSuccess(200, `Owners page sucessfully loaded`, Data.owners.slice(firstItem - 1, lastItem));
                } else {
                    lastResponse.setError(406, "Request level error", "22 - Owners array out of bounds.");
                };
            } else {
                lastResponse.setError(406, "Request level error", "23 - Page must be greater than zero.");
            };



        } else {
            lastResponse.setSuccess(200, "All owners sucessfully loaded", Data.owners);
        };
    };
    return lastResponse;
};
module.exports = {get };
const { Response } = require("../../../models/Response");
const { Data } = require("../../data");

function get(req) {
    let lastResponse = new Response();
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
            lastResponse.setSuccess(200, "All owners sucessfully soaded", Data.owners);
        };
    };
    return lastResponse;
};
module.exports = {get };
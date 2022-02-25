const { Data } = require("../../Controllers/data");
const { Response } = require("../../models/Response");
const { Owner } = require("../../models/Owner");


function controllerOwner(req, method) {
    if (req.method == "POST") {
        let lastResponse = new Response();
        const owner = new Owner(
            req.body.firstName,
            req.body.lastName,
            req.body.email,
            req.body.phoneNumber);
        /**Check for unique identifiers */
        if (owner.error) {
            lastResponse.setError(406, "Class Level Error", owner.errorList);
        } else {
            Data.owners.push(owner);
            lastResponse.setSuccess(201, "Owner Sucessfylly Created", owner);
        };
        return lastResponse;
    } else {
        if (req.method == "GET") {
            let lastResponse = new Response();
            if (req.query.id != null) {
                if (req.query.id > Data.owners.length || req.query.id < 0) {
                    lastResponse.setError(406, "Request Level Error", "21- Owner not Found.");
                } else {
                    lastResponse.setSuccess(200, "Owner Sucessfully Loaded", Data.owners[req.query.id - 1]);
                };
            } else {
                if (req.query.page != null) {
                    const page = parseInt(req.query.page);
                    if (page > 0) {
                        const firstItem = (req.query.page * 10) - 9;
                        if (Data.owners.length > firstItem + 9 || Data.owners.length > firstItem) {
                            const lastItem = firstItem + 9 > Data.owners.length ? Data.owners.length : firstItem + 9;
                            lastResponse.setSuccess(200, `Owners Page Sucessfully Loaded`, Data.owners.slice(firstItem - 1, lastItem));
                        } else {
                            lastResponse.setError(406, "Request Level Error", "22 - Owners array out of Bounds.");
                        };
                    } else {
                        lastResponse.setError(406, "Request Level Error", "23 - Page must be greater than zero.");
                    };
                } else {
                    lastResponse.setSuccess(200, "All Owners Sucessfully Loaded", Data.owners);
                };
                /**Paginate this */
            };
            return lastResponse;

        } else {
            /**Invalid Method */
        };
    }
}
module.exports = { controllerOwner };
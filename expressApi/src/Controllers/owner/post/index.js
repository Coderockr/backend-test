const { Response } = require("../../../models/Response");
const { Owner } = require("../../../models/Owner");
const { Data } = require("../../data");

function post(req) {
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
}
module.exports = { post };
const { Response } = require("../../models/Response")

function paginator(page, arrayToMap, String) {
    const lastResponse = new Response();

    if (page > 0) {
        const firstItem = (page * 10) - 9;

        if (arrayToMap.length > firstItem + 9 || arrayToMap.length > firstItem) {
            const lastItem = firstItem + 9 > arrayToMap.length ? arrayToMap.length : firstItem + 9;

            lastResponse.setSuccess(200, `${String} page sucessfully loaded`, arrayToMap.slice(firstItem - 1, lastItem));
        } else {

            lastResponse.setError(406, "Request level error", "22 - Array out of bounds.");
        };
    } else {
        lastResponse.setError(406, "Request level error", "23 - Page must be greater than zero.");
    }

    return lastResponse



}
module.exports = { paginator }
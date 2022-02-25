const { get } = require("./get");

function controllerWithdraw(req) {
    if (req.method == "GET") {
        return get(req)
    } else {
        if (req.method == "POST") {

        } else {
            /**Invalid method */
        }

    }
}

module.exports = { controllerWithdraw };
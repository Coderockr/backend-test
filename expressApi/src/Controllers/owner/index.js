const { post } = require("./post");
const { get } = require("./get");

function controllerOwner(req, method) {
    if (req.method == "POST") {
        return post(req)
    } else {
        if (req.method == "GET") {
            return get(req)
        } else {
            /**Invalid Method */
        };
    };
};
module.exports = { controllerOwner };
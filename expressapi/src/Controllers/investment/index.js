const { post } = require("./post");
const { get } = require("./get");

function controllerInvestment(req) {
    if (req.method == "POST") {
        return post(req)
    } else {
        if (req.method == "GET") {
            return get(req)
        };
    };
};

module.exports = { controllerInvestment };
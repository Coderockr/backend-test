const { Data } = require("../../Controllers/data")
const { Response } = require("../../models/Response")

function controllerWithdraw(req) {
    let lastResponse = new Response();
    if (req.method == "GET") {
        let lastResponse = new Response();
        const dateNow = new Date(Date.now())
        const index = Data.investments.findIndex(i => i.investmentId === req.body.investmentId);
        if (index < 0) {
            lastResponse.setError(406, "Request Level Error", "16 - Investment not found.")
        } else {
            const date = new Date(Date.parse(req.body.date))
            if (date.getTime() > dateNow.getTime() || !(date instanceof Date) || isNaN(date)) {
                lastResponse.setError(400, "Request Level Error", "17 - Invalid date to Withdraw.")
            } else {
                const sacado = Data.investments[index].withdraw(date);
                if (Data.investments[index].error) {
                    lastResponse.setError(400, "Class Level Error", Data.investments[index].errorList);
                } else {
                    lastResponse.setSuccess(200, `Withdrawn value: USD${sacado}`, Data.investments[index])
                }
            }
        }
        return lastResponse
    } else {
        if (req.method == "POST") {


        } else {
            /**Invalid method */
        }

    }
}

module.exports = { controllerWithdraw }
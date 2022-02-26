const { Error } = require("../../Controllers/error");
class Response {
    error = false;
    message = null;
    status = null;

    setError(status, message, errorList) {
        if (!this.error) {
            this.errorList = [];
            this.error = true;
        };
        this.message = message;
        this.status = status;
        if (errorList instanceof Array) {
            this.errorList.push(...errorList);
        } else {
            const timeStamp = new Date(Date.now());
            const error = new Error(timeStamp, errorList);
            this.errorList.push(error);
        };
    };
    setSuccess(status, message, objects) {
        if (this.error = false) {
            this.status = status;
            this.message = message;
            this.objects = [];
            if (objects instanceof Array) {
                this.objects.push(...objects);
            } else {
                this.objects.push(objects);
            };
        };
    };
};
module.exports = { Response };
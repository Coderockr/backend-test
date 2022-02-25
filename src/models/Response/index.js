class Response {
    error = false;
    message = null;
    status = null;

    setError(status, message, errorList) {
        this.message = message;
        this.status = status;
        this.errorList = [];
        if (errorList instanceof Array) {
            this.errorList.push(...errorList);
        } else {
            this.errorList.push(errorList);
        }
        this.error = true;
    }
    setSuccess(status, message, objects) {
        this.status = status;
        this.message = message;
        this.objects = [];
        if (objects instanceof Array) {
            this.objects.push(...objects);
        } else {
            this.objects.push(objects);
        }
    }
}
module.exports = { Response };
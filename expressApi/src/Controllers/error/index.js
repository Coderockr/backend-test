class Error {
    timestamp = new Date();
    message = "";

    constructor(timestamp, message) {
        this.timestamp = timestamp;
        this.message = message;
    };
};
module.exports = { Error };
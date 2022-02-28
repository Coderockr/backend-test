const { Error } = require("../../Controllers/data/error");
const { validator } = require("../../Controllers/data/validator");
class Owner {
    static counter = 0;
    _ownerId = null;
    _firstName = null;
    _lastName = null;
    _email = null;
    _phoneNumber = null;



    /**Getters */
    get ownerId() {
        return this._ownerId;
    };
    get firstName() {
        return this._firstName;
    };
    get lastName() {
        return this._lastName;
    };
    get email() {
        return this._email;
    };
    get phoneNumber() {
        return this._phoneNumber;
    };

    /**Setters */
    set firstName(firstName) {
        if (validator("text", firstName)) {
            this._firstName = firstName;
        } else {
            this.setError("01 - Invalid owner first name format.");
        };
    };

    set lastName(lastName) {
        if (validator("text", lastName)) {
            this._lastName = lastName;
        } else {
            this.setError("02 - Invalid owner last name format.");
        };
    };
    set email(email) {
        if (validator("email", email)) {
            this._email = email;
        } else {
            this.setError("03 - Invalid owner email format.");
        }

    }
    set phoneNumber(number) {
        if (validator("number", number)) {
            this._phoneNumber = parseInt(number);
        } else {
            this.setError("04 - Invalid owner phone number format.");
        };

    };

    /**Function */
    setError(message) {
        const timeStamp = new Date(Date.now());
        if (!this.error) {
            this.errorList = [];
            this.error = true;
        };
        const error = new Error(timeStamp, message);
        this.errorList.push(error);
    };

    /**Constructor */
    constructor(firstName, lastName, email, phoneNumber) {
        this.firstName = firstName;
        this.lastName = lastName;
        this.email = email;
        this.phoneNumber = phoneNumber;
        if (this.error) {
            this.setError("05 - Invalid parameters format to create an owner.");
        } else {
            Owner.counter++;
            this._ownerId = Owner.counter;
        };
    };
};
module.exports = { Owner };
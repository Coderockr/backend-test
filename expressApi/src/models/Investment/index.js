const { Error } = require("../../Controllers/error");
class Investment {
    static counter = 0;
    _investmentId = null;
    _ownerId = null;
    _creationDate = null;
    _initialAmount = 0;
    _atualAmount = null;



    /**Getters */
    get investmentId() {
        return this._investmentId;
    };
    get ownerId() {
        return this._ownerId;
    };
    get creationDate() {
        return this._creationDate;
    };
    get initialAmount() {
        return this._initialAmount;
    };
    get atualAmount() {
        return this._atualAmount;
    };
    get withdrawDate() {
        return this._withdrawDate;
    };
    get withdrawValue() {
        return this._withdrawValue;
    };

    /**Setters */

    set ownerId(ownerId) {
        const re = /^\d+(?:[.]\d{1,2}|$)$/;
        if (re.test(ownerId) || ownerId instanceof Number) {
            this._ownerId = parseInt(ownerId);
        } else {
            this.setError("06 - Invalid owner id format.");
        };
    };

    set creationDate(date) {
        const dateNow = new Date(Date.now());
        if ((!(isNaN(date))) &&
            date instanceof Date &&
            date.getTime() <= dateNow.getTime() &&
            this._creationDate == null) {
            this._creationDate = date;
        } else {
            this._creationDate = new Date(Date.now());
            this.setError("07 - Invalid setting of creation date.");
        };
    };

    set initialAmount(amount) {
        const dateNow = new Date(Date.now());
        const re = /^\d+(?:[.]\d{1,2}|$)$/;
        if ((re.test(amount) || amount instanceof Number) &&
            this._initialAmount == 0 && amount > 0) {
            this._initialAmount = amount;
            this._atualAmount = this.viewExpectedBalance(dateNow);
            this._atualAmount = amount;
        } else {
            this._initialAmount = null;
            this.setError("08 - Invalid setting of snitial amount.");
        }
    }

    set atualAmount(amount) {
        if (amount instanceof Number && amount > 0) {
            this._atualAmount = amount;
        } else {
            this.setError("09 - Invalid setting of atual amount.");
        }
    }

    /**Functions */
    setError(message) {
        const timeStamp = new Date(Date.now());
        if (!this.error) {
            this.errorList = [];
            this.error = true;
        };
        const error = new Error(timeStamp, message);
        this.errorList.push(error);
    }

    getInvestmentAge(date) {

        let age = (date.getTime() - this._creationDate.getTime()) / (1000 * 3600 * 24);
        if (age < 0) {
            this.setError("10 - Invalid investment Age, bad param passed.");
        }
        return age;
    }

    viewExpectedBalance(date) {
        /**May the taxes must be counted on This? */
        let monthsAfter;
        monthsAfter = (date.getFullYear() - this._creationDate.getFullYear()) * 12;
        monthsAfter -= this._creationDate.getMonth();
        monthsAfter += date.getMonth();
        date.getDate() < this._creationDate.getDate() ? monthsAfter -= 1 : null;


        let expectedBalance = this._initialAmount;
        if (monthsAfter > 0) {
            for (monthsAfter; monthsAfter > 0; monthsAfter--) {
                expectedBalance += expectedBalance * 0.0052;
            }
        } /**Set warning for no expected gain yet? */
        return expectedBalance;
    }

    withdraw(date) {
        if (!isNaN(this._withdrawDate)) {
            this.setError("14 - Unable to withdraw previous withdrawn investment.");
        } else {
            this._atualAmount = this.viewExpectedBalance(date);
            let withdraw = this._atualAmount;
            const profit = this._atualAmount - this._initialAmount;
            let taxes = 0;
            /**Checking if withdraw date is greater than creation date*/
            const dateNow = new Date(Date.now());
            if (this._creationDate.getTime() < date.getTime() && date.getTime() <= dateNow.getTime()) {
                let investmentAge = this.getInvestmentAge(date);
                /** Should we make this time windows and taxes value settable? */
                if (profit > 0) {
                    if (investmentAge < 365) {
                        taxes = profit * 0.225;

                    } else if ((investmentAge < 730)) {
                        taxes = profit * 0.185;
                    } else {
                        taxes = profit * 0.15;
                    };
                };
                this._withdrawDate = date;
                withdraw -= taxes;
                this._withdrawValue = withdraw.toFixed(2);
                /**Round or truncate? */

                return this._withdrawValue;
            } else {
                this.setError("11 - Cannot withdraw to past from creation date or in future from now.");
            };
        };
    };

    /**Class constructor */
    constructor(owner, creationDate, amount) {
        this.ownerId = owner;
        this.creationDate = creationDate;
        this.initialAmount = amount;
        if (this.owner === null || this.creationDate === null || this.amount === null) {
            this.setError("12 - Invalid parameters format to create an investment.");

        } else {
            Investment.counter++;
            this._investmentId = Investment.counter;
        };
    };
};
module.exports = { Investment };
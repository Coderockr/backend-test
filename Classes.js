class Owner {
    static counter = 0
    _ownerId = null
    _firstName = null;
    _lastName = null;
    _email = null;
    _phoneNumber = null;


    /**Getters */
    get ownerId() {
        return this._ownerId
    }
    get lastName() {
        return this._lastName
    }
    get firstName() {
        return this._firstName
    }
    get email() {
        return this._email
    }
    get phoneNumber() {
        return this._phoneNumber
    }

    /**Setters */
    set firstName(firstName) {
        const re = /^([a-zA-ZÀ-ÿ\u00f1\u00d1]*)+$/;
        if (re.test(firstName)) {
            this._firstName = firstName
        }

    }
    set lastName(lastName) {
        const re = /^([a-zA-ZÀ-ÿ\u00f1\u00d1]*)+$/;
        if (re.test(lastName)) {
            this._lastName = lastName
        }

    }
    set email(email) {
        const re = /^((?!\.)[\w_.]*[^.])(@\w+)(\.\w+(\.\w+)?[^.\W])$/;
        if (re.test(email)) {
            this._email = email
        }
    }
    set phoneNumber(number) {
            if (number instanceof Number) {
                /**Lead with Float */
                this._phoneNumber = number
            } else {
                const re = /^[0-9]+$/
                if (re.test(number)) {
                    this._phoneNumber = parseInt(number)
                }
            }
        }
        /**Constructor */
    constructor(firstName, lastName, email, phoneNumber) {
        this.firstName = firstName
        this.lastName = lastName
        this.email = email
        this.phoneNumber = phoneNumber
        if (this._firstName === null || this._lastName === null ||
            this._email === null || this._phoneNumber === null) {
            return null
                /**Should I Retrieve a creation error? */
        }
        Owner.counter++
            this._ownerId = Owner.counter

    }


}

/** Check for unnecessary validations inside getters and setters */
class Investment {
    _owner = null;
    _creationDate = null;
    _initialAmount = 0;
    _atualAmount = null;

    /**Setters */
    set owner(owner) {
        if (owner instanceof Owner) {
            this._owner = owner;
        } else {
            /**Should I Retrieve a creation error? */
        }
    }

    set creationDate(date) {
        const dateNow = new Date(Date.now())
        if (date instanceof Date && date.getTime() <= dateNow.getTime()) {
            this._creationDate = date;
        } else {
            /**Should I Retrieve a creation error? */
        }
    }

    set initialAmount(amount) {
        const dateNow = new Date(Date.now())
        const re = /^\d+(?:[.]\d{1,2}|$)$/
        if ((re.test(amount) || amount instanceof Number) &&
            this._initialAmount == 0 && amount > 0) {
            this._initialAmount = amount
            this._atualAmount = this.viewExpectedBalance(dateNow)
            this._atualAmount = amount
        } else {
            this._initialAmount = null;
            /**Should I Retrieve a creation error? */
        }
    }

    set atualAmount(amount) {
        if (amount instanceof Number && amount > 0) {
            this._atualAmount = amount;
        } else {

            /**Should I Retrieve a creation error? */
        }
    }

    /**Getters */
    get owner() {
        return this._owner;
    }
    get creationDate() {
        return this._creationDate;
    }
    get initialAmount() {
        return this._initialAmount;
    }
    get atualAmount() {
        return this._atualAmount;
    }

    /**Functions */
    getInvestmentAge(date) {
        let age = (this._creationDate.getTime() - date.getTime()) / (1000 * 3600 * 24)
        return age
    }

    viewExpectedBalance(date) {
        /**May the taxes must be counted on This? */
        let monthsAfter = date.getMonth() - this._creationDate.getMonth()
        date.getDate() < this._creationDate ? monthsAfter -= 1 : null
        let expectedBalance = this._initialAmount
        if (monthsAfter > 0) {
            for (monthsAfter; monthsAfter > 0; monthsAfter--) {
                expectedBalance += expectedBalance * 0.0052
            }
        }
        return expectedBalance
    }

    withdraw(date) {
        this._atualAmount = this.viewExpectedBalance(date)
        let withdraw = this._amount;
        const profit = this._amount - this._initialAmount;
        let taxes = 0;
        /**Checking if withdraw date is greater than creation date*/
        const dateNow = new Date(Date.now())
        if (this._creationDate.getTime() < date.getTime() && date.getTime() <= dateNow.getTime()) {
            let investmentAge = this.getInvestmentAge(date)
                /** Should we make this time windows and taxes value settable? */
            if (investmentAge < 365) {
                taxes = profit * 0.225

            } else if ((investmentAge < 730)) {
                taxes = profit * 0.185
            } else {
                taxes = profit * 0.15
            }
            this.amount = 0;
            withdraw -= taxes;
            return withdraw
        } else {
            return null
                /**Should I Retrieve a withdraw error? */
        }
    }

    /**Class constructor */
    constructor(owner, creationDate, amount) {
        this.owner = owner;
        this.creationDate = creationDate;
        this.initialAmount = amount;
        if (this.owner === null || this.creationDate === null || this.amount === null) {
            return null
                /**Should I Retrieve a creation error? */
        }

    }
}
module.exports = { Owner, Investment }
    /**To do:
     * Enhance Regex'es
     * Retrieve creation errors for classes on constructors
     * Retrieve creation errors for setters
     * Retrieve Withdraw error
     * Choose the best default of Date do return
     */
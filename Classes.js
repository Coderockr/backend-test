class Owner {
    static counter = 0
    static reString = /^([a-zA-ZÀ-ÿ\u00f1\u00d1]*)+$/;
    static reEmail = /^((?!\.)[\w_.]*[^.])(@\w+)(\.\w+(\.\w+)?[^.\W])$/;
    static reNumber = /^[0-9]+$/
    _ownerId = null
    _firstName = null;
    _lastName = null;
    _email = null;
    _phoneNumber = null;
    error = false
    errorList = []


    /**Getters */
    get ownerId() {
        return this._ownerId
    }
    get firstName() {
        return this._firstName
    }
    get lastName() {
        return this._lastName
    }
    get email() {
        return this._email
    }
    get phoneNumber() {
        return this._phoneNumber
    }

    /**Setters */
    set firstName(firstName) {
        if (Owner.reString.test(firstName) && firstName != undefined) {
            this._firstName = firstName
        } else {
            this.setError("01 - Invalid Owner First Name Format")
        }

    }
    set lastName(lastName) {
        if (Owner.reString.test(lastName) && lastName != undefined) {
            this._lastName = lastName
        } else {
            this.setError("02 - Invalid Owner Last Name Format")
        }


    }
    set email(email) {
        if (Owner.reEmail.test(email)) {
            this._email = email
        } else {
            this.setError("03 - Invalid Owner Email Format")
        }

    }
    set phoneNumber(number) {
        if (Owner.reNumber.test(number) || number instanceof Number) {
            this._phoneNumber = parseInt(number)
        } else {
            this.setError("04 - Invalid Owner Phone Number Format")
        }

    }

    /**Function */
    setError(message) {
        this.error = true
        this.errorList.push(message)
    }

    /**Constructor */
    constructor(firstName, lastName, email, phoneNumber) {
        this.firstName = firstName
        this.lastName = lastName
        this.email = email
        this.phoneNumber = phoneNumber
        if (this.error) {
            this.setError("05 - Invalid parameters format to create a Owner")
        } else {
            Owner.counter++
                this._ownerId = Owner.counter
        }

    }


}

/** Check for unnecessary validations inside getters and setters */
class Investment {
    static counter = 0;
    _investmentId = null;
    _ownerId = null;
    _creationDate = null;
    _initialAmount = 0;
    _atualAmount = null;
    error = false
    errorList = []


    /**Setters */

    set ownerId(ownerId) {
        const re = /^\d+(?:[.]\d{1,2}|$)$/
        if (re.test(ownerId) || ownerId instanceof Number) {
            this._ownerId = parseInt(ownerId)
        } else {
            this.setError("06 - Invalid Owner ID Format")
        }
    }

    set creationDate(date) {
        const dateNow = new Date(Date.now())
        if (date instanceof Date && date.getTime() <= dateNow.getTime() && this._creationDate == null) {
            this._creationDate = date;
        } else {
            this.setError("07 - Invalid Setting of Creation Date")
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
            this.setError("08 - Invalid Setting of Initial Amount")
        }
    }

    set atualAmount(amount) {
        if (amount instanceof Number && amount > 0) {
            this._atualAmount = amount;
        } else {
            this.setError("09 - Invalid Setting of Atual Amount")
        }
    }

    /**Getters */
    get ownerId() {
        return this._ownerId;
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
    setError(message) {
        this.error = true
        this.errorList.push(message)
    }

    getInvestmentAge(date) {

        let age = (this._creationDate.getTime() - date.getTime()) / (1000 * 3600 * 24)
        if (age < 0) {
            this.setError("10 - Invalid Investment Age, bad param passed.")
        }
        return age
    }

    viewExpectedBalance(date) {
        /**May the taxes must be counted on This? */
        var monthsAfter;
        monthsAfter = (date.getFullYear() - this._creationDate.getFullYear()) * 12;
        monthsAfter -= this._creationDate.getMonth();
        monthsAfter += date.getMonth();

        date.getDate() < this._creationDate.getDate() ? monthsAfter -= 1 : null
        let expectedBalance = this._initialAmount
        if (monthsAfter > 0) {
            for (monthsAfter; monthsAfter > 0; monthsAfter--) {
                expectedBalance += expectedBalance * 0.0052
            }
        } else {
            this.setError("11 - No expected gain Yet")
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
            this.setError("12 - Cannot withdraw to past from creation date or in future from now")
        }
    }

    /**Class constructor */
    constructor(owner, creationDate, amount) {
        this.ownerId = owner;
        this.creationDate = creationDate;
        this.initialAmount = amount;
        if (this.owner === null || this.creationDate === null || this.amount === null) {
            this.setError("05 - Invalid parameters format to create a Investment")

        } else {
            Investment.counter++
                this._investmentId = Investment.counter
        }

    }
}
module.exports = { Owner, Investment }
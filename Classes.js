/**Enhance Class Owner */
class Owner {
    _firstName = null;
    _lastName = null;
    _email = null;
    _phoneNumber = null;


    /**Getters */
    get email() {
        return this._email
    }
    get phoneNumber() {
        return this._phoneNumber
    }

    /**Setters */
    set firstName(firstName) {
        const re = / ^(?=.*?[A-Za-z])[A-Za-z+]+$/;
        if (re.test(firstName)) {
            this._firstName = firstName
        }

    }
    set email(email) {
        /**Enhance this regex to prevent sqlinjection or other threats */
        const re = / ^((?!\.)[\w_.]*[^.])(@\w+)(\.\w+(\.\w+)?[^.\W])$ /;
        if (re.test(email)) {
            this._email = email
        }
    }
    set phoneNumber(number) {
            if (number instanceof Number) {
                /**Lead with Float */
                this._phoneNumber = number
            }
        }
        /**Constructor */
    constructor(firstName, lastName, email, phoneNumber) {

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
        if (date instanceof Date && date.getTime() <= Date.now().getTime()) {
            this._creationDate = date;
        } else {
            /**Should I Retrieve a creation error? */
        }
    }

    set initialAmount(amount) {
        if (amount instanceof Number && this._initialAmount == 0 && amount > 0) {
            this._initialAmount = amount
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
        return age > 0 ? age : 0
            /**Should I Retrieve a querry error? */
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
        /**Verifica se data de saque é maior que a data de criação*/
        if (this._creationDate.getTime() < date.getTime() && date.getTime() <= Date.now().getTime()) {
            let investmentAge = this.getInvestmentAge(date)
                /** Colocar o valor das taxas e períodos em variáveis para editar depois? */
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
        /**Se ao final do constructor algum campo der null, retornar erro. */
        /** */
        if (this.owner === null || this.creationDate === null || this.amount === null) {
            return null
                /**Should I Retrieve a creation error? */
        }

    }
}
module.exports = { Owner, Investment }
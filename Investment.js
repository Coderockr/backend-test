export class Investment {
    owner;
    _creationDate;
    _initialAmount = 0;
    _amount;

    /**Setters */


    set creationDate(date) {
        if (date instanceof Date) {
            /**Validar data maior ou igual*/
            this._creationDate = date;

        } else {
            if (date === undefined) {
                this._creationDate = Date.now()
            } else {
                /**Retornar erro se não vazio */
                this._creationDate === null;
            }
        }
    }

    set initialAmount(amount) {
        if (amount instanceof Number && this._initialAmount == 0 && amount > 0) {
            this._initialAmount = amount
        } else {
            /**Tratar se valor negativo */
            /**Retornar erro se vazio */
            this._amount = null;
        }
    }

    set amount(amount) {
        if (amount instanceof Number && this._initialAmount == 0 && amount > 0) {
            this._initialAmount = amount;
        } else {
            /**Tratar se valor negativo */
            /**Retornar erro se vazio */
            this._amount = null;
        }
    }

    /**Getters */
    get creationDate() {
        return this._creationDate;
    }


    get amount() {
        return this.amount;
    }

    /**Functions */
    viewExpectedBalance(date) {
        /**
    1. Expected balance é a soma of the invested amount and the [gains][]. 
    o investimento paga 0.52% todo mês na mesma data da criação.

    Given that the gain is paid every month, it should be treated as [compound gain][],
    which means that every new period (month)
    the amount gained will become part of the investment balance for the next payment.
    
    */

    }

    withdraw(date) {
        let withdraw = this._amount;
        const profit = this._amount - this._initialAmount;
        let taxes = 0;
        /** caso data de criação - agora = 365, 366 - 730,731 +  */
        /**Verificar datas de saque inferiores à data de criação */
        var Difference_In_Time = this._creationDate.getTime() - date.getTime()

        if (this._creationDate) {

        }
        /**
         *2. Saques no passado ou presente, nunca no futuro.
         *3. Taxas são aplicadas ao saque antes de exibir o valor final. 
    Taxa só se aplica ao lucro.


The tax percentage changes according to the age of the investment:
* Menos de um ano: 22.5%.
* Entre um e dois anos 18.5%.
* Mais que dois anos 15%.
       */
        this.amount = 0
        return withdraw
    }


    /**Class constructor */
    constructor(owner, creationDate, amount) {
        this.owner = owner;
        this.creationDate = creationDate;
        this.initialAmount = amount;

    }
}
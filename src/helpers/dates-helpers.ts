import { Injectable } from "@nestjs/common";

@Injectable()
export default class DatesHelpers {
    getMonthDifference(startDate, endDate) {
      
        let monthDifference = 
            endDate.getMonth() -
            startDate.getMonth() +
            12 * (endDate.getFullYear() - startDate.getFullYear());

        if(startDate.getDate() > endDate.getDate()) {
            monthDifference--;
        }

        return monthDifference;
    }

    verifyFutureDate(startDate, endDate) {
        if(startDate > endDate) {
            return true;
        }
        return false;
    }
}


class CalculateGainProvider {

  calculateAmount(capital: number, numberMonths: number): number {
    let amount = capital * ((1 + 0.0052) ** numberMonths);
    return amount;
  }

  calculateRate(gain: number, numberMonths: number): number {
    let rate = 0;

    if (numberMonths < 12) {
      rate = gain * 0.225;
    } else if (numberMonths >= 12 && numberMonths <= 24) {
      rate = gain * 0.185;
    } else {
      rate = gain * 0.15;
    }

    return rate;
  }
}

export { CalculateGainProvider };

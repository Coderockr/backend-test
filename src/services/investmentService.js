const calculateEarnings = (initialValue, months) => {
    if (initialValue <= 0 || months < 0) {
        throw new Error('Os valores de "initialValue" e "months" devem ser positivos.');
    }
    const monthlyRate = 0.0052;
    return initialValue * Math.pow(1 + monthlyRate, months) - initialValue;
};

const calculateTaxes = (earnings, months) => {
    if (earnings < 0 || months < 0) {
        throw new Error('Os valores de "earnings" e "months" devem ser positivos.');
    }
    let taxRate;
    if (months < 12) taxRate = 0.225;
    else if (months < 24) taxRate = 0.185;
    else taxRate = 0.15;

    return earnings * taxRate;
};

module.exports = { calculateEarnings, calculateTaxes };

const calculateEarnings = (initialValue, months) => {
    const monthlyRate = 0.0052;
    return initialValue * Math.pow(1 + monthlyRate, months) - initialValue;
};

const calculateTaxes = (earnings, months) => {
    let taxRate;
    if (months < 12) taxRate = 0.225;
    else if (months < 24) taxRate = 0.185;
    else taxRate = 0.15;

    return earnings * taxRate;
};

module.exports = { calculateEarnings, calculateTaxes };

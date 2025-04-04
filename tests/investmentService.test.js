const { calculateEarnings, calculateTaxes } = require('../src/services/investmentService');

test('Cálculo de rendimentos', () => {
    const earnings = calculateEarnings(1000, 12);
    expect(earnings).toBeCloseTo(62.77, 2);
});

test('Cálculo de impostos para menos de 1 ano', () => {
    const taxes = calculateTaxes(200, 6);
    expect(taxes).toBe(45);
});

test('Cálculo de impostos para entre 1 e 2 anos', () => {
    const taxes = calculateTaxes(200, 18);
    expect(taxes).toBe(37);
});

test('Cálculo de impostos para mais de 2 anos', () => {
    const taxes = calculateTaxes(200, 30);
    expect(taxes).toBe(30);
});

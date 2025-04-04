module.exports = (req, res, next) => {
    const { owner, creationDate, value } = req.body;

    if (!owner || typeof owner !== 'string') {
        return res.status(400).send({ error: 'Proprietário inválido.' });
    }

    if (!creationDate || isNaN(Date.parse(creationDate))) {
        return res.status(400).send({ error: 'Data de criação inválida.' });
    }

    const creationDateObj = new Date(creationDate);
    const currentDate = new Date();
    if (creationDateObj > currentDate) {
        return res.status(400).send({ error: 'A data de criação não pode ser no futuro.' });
    }

    if (value == null || typeof value !== 'number' || value < 0) {
        return res.status(400).send({ error: 'Valor do investimento inválido.' });
    }

    next();
};

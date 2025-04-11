module.exports = (err, req, res, next) => {
    console.error(`[${new Date().toISOString()}] Erro na requisição ${req.method} ${req.url}`);
    console.error(err.stack);
    res.status(500).send({ error: 'Ocorreu um erro interno no servidor.' });
};

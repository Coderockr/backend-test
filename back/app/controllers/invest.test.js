const { expect } = require('@jest/globals')
const request = require('supertest')
const app = require('../../server')



describe('Post Create Investiment ENDPOINT', () => {
    
    it('Criando um investimento com data atual e valor positivo', async () => {
        const res = await request(app)
        .get('/api/owner/list');
        let owner = res.body[0].id;

        const res2 = await request(app)
        .post('/api/invest')
        .send({
         owner:owner,
         valueInitalInvest: 292.01,
         dateInvest: new Date(new Date().getTime() + (3600000*(-3))),
         withdrawn:false
       })
        expect(res2.statusCode).toEqual(201)
    })
    it('Criando um investimento com data passada e valor positivo', async () => {
        const res = await request(app)
        .get('/api/owner/list');
        let owner = res.body[0].id;

        const res2 = await request(app)
        .post('/api/invest')
        .send({
         owner:owner,
         valueInitalInvest: 1000.22,
         dateInvest: new Date("2016-05-31T16:00:00Z"),
         withdrawn:false
       })
        expect(res2.statusCode).toEqual(201)
    })
    it('Criando um investimento com 1 ano fechado', async () => {
        const res = await request(app)
        .get('/api/owner/list');
        let owner = res.body[0].id;

        const res2 = await request(app)
        .post('/api/invest')
        .send({
         owner:owner,
         valueInitalInvest: 1000.22,
         dateInvest: new Date("2021-02-27T16:00:00Z"),
         withdrawn:false
       })
        expect(res2.statusCode).toEqual(201)
    })
    it('Criando um investimento com mais de 1 ano fechado', async () => {
        const res = await request(app)
        .get('/api/owner/list');
        let owner = res.body[0].id;

        const res2 = await request(app)
        .post('/api/invest')
        .send({
         owner:owner,
         valueInitalInvest: 1000.22,
         dateInvest: new Date("2020-07-27T16:00:00Z"),
         withdrawn:false
       })
        expect(res2.statusCode).toEqual(201)
    })
    it('Criando um investimento com valor negativo', async () => {
        const res = await request(app)
        .get('/api/owner/list');
        let owner = res.body[0].id;

        const res2 = await request(app)
        .post('/api/invest')
        .send({
         owner:owner,
         valueInitalInvest: -292.30,
         dateInvest: new Date("2016-05-18T16:00:00Z"),
         withdrawn:false
       })
        expect(res2.statusCode).toEqual(400)
    })
    it('Criando um investimento futuro', async () => {
        const res = await request(app)
        .get('/api/owner/list');
        let owner = res.body[0].id;

        const res2 = await request(app)
        .post('/api/invest')
        .send({
         owner:owner,
         valueInitalInvest: 292.00,
         dateInvest: new Date("2023-05-18T16:00:00Z"),
         withdrawn:false
       })
        expect(res2.statusCode).toEqual(400)
    })
})

describe('GET View Investiment ENDPOINT', () => {
    it('Retorno deve ser a soma do valor investido + ganhos (calcular o numero de meses fechados, multiplicar por 0,52% e com reaplicacao)', async () => {

        const res2 = await request(app)
        .get('/api/invest/list');
        let id = res2.body[0].id;

        const res = await request(app)
        .get('/api/invest/'+id)
        
        expect(res.statusCode).toEqual(201)
    })
})

describe('POST Withdrawal Investiment ENDPOINT', () => {
    it('Retirando a soma do valor investido + ganhos, aplicando o imposto na retirada apenas do lucro', async () => {
        const res2 = await request(app)
        .get('/api/invest/list');
        let id = res2.body[0].id;

        const res3 = await request(app)
        .get('/api/invest/'+id);

        let dateInvest = res2.body[0].dateInvest;
        let valueWithdrawn = res3.body.result;
        
        const res = await request(app)
        .post('/api/invest/withdrawal/').send({
            id_invest:id,
            dataWithdrawn:new Date(new Date().getTime() + (3600000*(-3))),
            dateInvest:dateInvest,
            valueWithdrawn:valueWithdrawn
        })
        expect(res.statusCode).toEqual(201)
    })
    it('Retirada acontecendo no futuro', async () => {
        const res2 = await request(app)
        .get('/api/invest/list');
        let id = res2.body[0].id;

        const res3 = await request(app)
        .get('/api/invest/'+id+"?dataWithdrawn=2050-05-05");

        expect(res3.statusCode).toEqual(400)

    })
    it('Retirada acontecendo antes da criaçao do investimento', async () => {
        const res2 = await request(app)
        .get('/api/invest/list');
        let id = res2.body[0].id;

        const res3 = await request(app)
        .get('/api/invest/'+id+"?dataWithdrawn=1900-01-01");

        expect(res3.statusCode).toEqual(400)
    })
})

describe('GET List of Investiments ENDPOINT', () => {
    it('Retorno deve ser a lista de investimento de "uma" pessoa pagina 1', async () => {
        const res2 = await request(app)
        .get('/api/owner/list');
        let owner = res2.body[0].id;

        const res = await request(app)
        .get('/api/invest/list?owner='+owner)

        expect(res.statusCode).toEqual(200)
    })
    it('Retorno deve ser a lista de investimento de "uma" pessoa pagina 2', async () => {
        const res2 = await request(app)
        .get('/api/owner/list');
        let owner = res2.body[0].id;

        const res = await request(app)
        .get('/api/invest/list?owner='+owner+'&pg=2')
        expect(res.statusCode).toEqual(200)
    })
})


describe('Post Create a Owner ENDPOINT', () => {
    it('Criando um investidor apenas com o nome', async () => {
        const res = await request(app)
        .post('/api/owner')
        .send({
            owner_name:"Joao Phillips da Silva"
        })
        expect(res.statusCode).toEqual(201)
    })
    
})
describe('GET List of Owners ENDPOINT', () => {
    it('Retorno deve ser a lista de investidores cadastrados', async () => {
        const res = await request(app)
        .get('/api/owner/list')

        expect(res.statusCode).toEqual(200)
    })
   
})
import Server from '../../server'
import { Application } from 'express'
import supertest from 'supertest'
import { disconnectMongoose } from '../helpers/database'
import {
  deleteInvestmentQuery,
  getInvestmentQuery,
  getInvestmentsByUserQuery,
  makeInvestmentQuery,
  makeUserQuery
} from '../helpers/query'
import { addMonths, subMonths } from 'date-fns'
import { MONTLY_GAIN_FEE } from '../../modules/investment/InvestmentModel'

const appServer = new Server()
let app: Application
let agent: supertest.SuperTest<supertest.Test>

jest.useRealTimers()

beforeAll(async () => {
  app = await appServer.Start('test')
})

beforeEach(() => {
  agent = supertest(app)
})

afterAll(disconnectMongoose)

describe('Mutation - Investment Creation', () => {
  test('Should failed because date is in the future', async () => {
    const user = await agent.post('/graphql').send({ query: makeUserQuery() })

    const result = await agent.post('/graphql').send({
      query: makeInvestmentQuery({
        userId: user?.body?.data?.createUser?.id,
        customDate: addMonths(new Date(), 5)
      })
    })

    expect(result.body.errors).toBeTruthy()
  })

  test('Should failed because user id is invalid', async () => {
    const result = await agent.post('/graphql').send({
      query: makeInvestmentQuery({
        userId: 'invalid_id'
      })
    })

    expect(result.body.errors).toBeTruthy()
  })

  test('Should failed because initialValue is negative', async () => {
    const user = await agent.post('/graphql').send({ 
      query: makeUserQuery() 
    })

    const result = await agent.post('/graphql').send({
      query: makeInvestmentQuery({
        userId: user?.body?.data?.createUser?.id,
        initialValue: -20
      })
    })

    expect(result.body.errors).toBeTruthy()
  })

  test('Should create an investment with success', async () => {
    const user = await agent.post('/graphql').send({ 
      query: makeUserQuery() 
    })

    const result = await agent.post('/graphql').send({
      query: makeInvestmentQuery({
        userId: user?.body?.data?.createUser?.id
      })
    })

    expect(result.status).toBe(200)
    expect(result.body).toBeInstanceOf(Object)
  })

})

describe('Query - Get Investment by Id ', () => {
  test('Should fail because investment id is invalid', async () => {
    const result = await agent.post('/graphql').send({
      query: getInvestmentQuery('invalid id')
    })

    expect(result.body.errors).toBeTruthy()
  })

  test('Should return the investment with success', async () => {
    const { body: userBody } = await agent.post('/graphql').send({ query: makeUserQuery() })
    
    const { body: investmentBody } = await agent.post('/graphql').send({
      query: makeInvestmentQuery({
        userId: userBody?.data?.createUser?.id
      })
    })

    const result = await agent.post('/graphql').send({
      query: getInvestmentQuery(investmentBody?.data?.createInvestment?.id)
    })

    expect(result.status).toBe(200)
    expect(result.body).toBeInstanceOf(Object)
  })

  test('Should return a valid gain amount', async () => {
    const { body: makeUser } = await agent.post('/graphql').send({ 
      query: makeUserQuery() 
    })

    const { body: makeInvestment } = await agent.post('/graphql').send({
      query: makeInvestmentQuery({
        userId: makeUser?.data?.createUser?.id,
        customDate: subMonths(new Date(), 1)
      })
    })

    const { body: getInvestment, status } = await agent.post('/graphql').send({
      query: getInvestmentQuery(makeInvestment?.data?.createInvestment?.id)
    })

    const result = getInvestment.data
    const { initialAmount } = result?.getInvestmentByid

    const gain = (initialAmount * MONTLY_GAIN_FEE) / 100
    expect(status).toBe(200)
    expect(result?.getInvestmentByid?.gainAmount).toBe(gain)
  })
})

describe('Query - Get all investments by user id', () => {

  test(`Should failed because user id is invalid`, async () => {
    const result = await agent.post('/graphql').send({
      query: getInvestmentsByUserQuery('invalid id')
    })

    expect(result.body.errors).toBeTruthy()
  })

  test('Should return investments with success', async () => {
    const { body: makeUser } = await agent.post('/graphql').send({ 
      query: makeUserQuery() 
    })    

    Array.from(Array(5)).map(async () => {
      await agent.post('/graphql').send({
        query: makeInvestmentQuery({
          userId: makeUser?.data?.createUser?.id
        })
      })
    })

    const { body } = await agent.post('/graphql').send({
      query: getInvestmentsByUserQuery(makeUser?.data?.createUser?.id)
    })
    
    expect(body?.data?.returnAllInvestmentsByUser).toBeInstanceOf(Array)
    expect(body.errors).toBeFalsy()    
  })
})

describe('Mutation - Delete Investment by id', () => {
  test('Should fail because investment id is invalid', async () => {    
    const result = await agent.post('/graphql').send({
      query: deleteInvestmentQuery("invalid id")
    })

    expect(result.body.errors).toBeTruthy()
  })

  test('Should delete an investment with success', async () => {
    const { body: makeUser } = await agent.post('/graphql').send({ 
      query: makeUserQuery() 
    })
    const { body: investmentBody } = await agent.post('/graphql').send({
      query: makeInvestmentQuery({
        userId: makeUser?.data?.createUser?.id
      })
    })

    const result = await agent.post('/graphql').send({
      query: deleteInvestmentQuery(investmentBody?.data?.createInvestment?.id)
    })

    expect(result.status).toBe(200)
    expect(result.body).toBeInstanceOf(Object)
  })
})
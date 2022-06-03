import { Application } from "express"
import Server from '../../server'
import supertest from 'supertest'
import { disconnectMongoose } from '../helpers/database'
import { makeInvestmentQuery, makeUserQuery, withdrawalInvestment } from "../helpers/query"
import { addMonths } from "date-fns"

const appServer = new Server()
let app: Application
let agent: supertest.SuperTest<supertest.Test>

beforeAll(async () => {
    app = await appServer.Start('test')
  })
  
beforeEach(() => {
    agent = supertest(app)
  })
  
afterAll(disconnectMongoose)

describe('Mutation - Withdrawal Investment', () => {
    test('Should failed because investiment id is invalid', async () => {

        const result = await agent.post('/graphql').send({
            query: withdrawalInvestment({
                investmentId: 'invalid_id'
            })
        })
    
        expect(result.body.errors).toBeTruthy()
    })

    test('Should failed because date is in the future', async () => {
        const { body: bodyUser } = await agent.post('/graphql').send({ 
            query: makeUserQuery() 
          })

        const { body: bodyInvestment } = await agent.post('/graphql').send({ 
            query: makeInvestmentQuery({
                userId: bodyUser?.data?.createUser.id,  
                initialValue: 200
            })
        })

        const result = await agent.post('/graphql').send({
            query: withdrawalInvestment({
                investmentId: bodyInvestment?.data?.createInvestment?.id,
                creationDate: addMonths(new Date(), 1)
            })
        })

        expect(result.body.errors).toBeTruthy()
    })
})
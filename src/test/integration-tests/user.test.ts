import { Application } from 'express'
import Server from '../../server'
import supertest from 'supertest'
import { disconnectMongoose } from '../helpers/database'
import { deleteUserQuery, makeUserQuery } from '../helpers/query'
import { addMonths } from 'date-fns'

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

describe('Mutation - User Creation', () => {
  test('Should fail because born date is invalid', async () => {
    const result = await agent.post('/graphql').send({
      query: makeUserQuery({ bornDate: addMonths(new Date(), 1) })
    })

    expect(result.body.errors).toBeTruthy()
  })

  test('Should fail because email already exists', async () => {
    await agent.post('/graphql').send({
      query: makeUserQuery({ email: 'testuser@mail.com' })
    })

    const result = await agent.post('/graphql').send({
      query: makeUserQuery({ email: 'testuser@mail.com' })
    })
    expect(result.body.errors).toBeTruthy()
  })

  test('Should fail because email is invalid', async () => {
    const result = await agent.post('/graphql').send({
      query: makeUserQuery({ email: 'invalid mail' })
    })

    expect(result.body.errors).toBeTruthy()
  })

  test('Should create a user with success', async () => {
    const result = await agent.post('/graphql').send({
      query: makeUserQuery()
    })

    expect(result.status).toBe(200)
    expect(result.body?.data?.createUser).toBeInstanceOf(Object)
  })
})

describe('Mutation - User Deletion', () => {
  test('Should fail because user id is invalid', async () => {

    const result = await agent.post('/graphql').send({
      query: deleteUserQuery('invalid id')
    })

    expect(result.body.errors).toBeTruthy()
  })

  test('Should delete user with success', async () => {
    const { body: bodyUser } = await agent.post('/graphql').send({
      query: makeUserQuery()
    })

    const result = await agent.post('/graphql').send({
      query: deleteUserQuery(bodyUser?.data?.createUser?.id)
    })

    expect(result.status).toBe(200)
    expect(result.body?.data?.deleteUser).toBeInstanceOf(Object)
  })
})

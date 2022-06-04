import { ApolloServerPluginLandingPageGraphQLPlayground } from 'apollo-server-core'
import { ApolloServer, ExpressContext } from 'apollo-server-express'
import express, { Application } from 'express'
import mongoose from 'mongoose'
import { buildSchema } from 'type-graphql'
import { InvestmentResolver } from './modules/investment/InvestmentResolver'
import { UserResolver } from './modules/user/UserResolver'
import { WithDrawalResolver } from './modules/withdrawal/WithdrawalResolver'

export default class ServerService {
  public static async ConnectToDatabase(node_env = 'dev') {
    try {
      await mongoose.connect((node_env === 'dev' ? process.env.MONGO_URI : process.env.MONGO_TEST_URI) as string)
    } catch (e) {
      console.log(`Could not connect database, ${e}`)
    }
  }

  public async generateSchema() {
    return await buildSchema({
      resolvers: [UserResolver, InvestmentResolver, WithDrawalResolver],
      emitSchemaFile: true,
    })
  }

  private readonly app: Application
  private server: ApolloServer<ExpressContext>

  constructor() {
    this.app = express()
  }

  public Server = (): ApolloServer<ExpressContext> => this.server 

  public async Start(node_env?: 'test' | 'dev') {
    const schema = await this.generateSchema()
    this.server = new ApolloServer({
      schema,
      plugins: [ApolloServerPluginLandingPageGraphQLPlayground]
    })

    await ServerService.ConnectToDatabase(node_env)

    await this.server.start()
    this.server.applyMiddleware({ app: this.app })

    return this.app
  }
}

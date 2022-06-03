// import { ApolloServerPluginLandingPageGraphQLPlayground } from "apollo-server-core";
// import express from "express";
import "reflect-metadata";
// import { buildSchema } from "type-graphql";
import dotenv from 'dotenv'
dotenv.config()
// import mongoose from 'mongoose'
// import { InvestmentResolver } from "./modules/investment/InvestmentResolver";
// import { UserResolver } from "./modules/user/UserResolver";
// import { WithDrawalResolver } from "./modules/withdrawal/WithdrawalResolver";
// import { ApolloServer } from "apollo-server-express";
import Server from "./server";

// export const Server = async () => {
//     console.log(process.env.MONGO_URI)
//     const schema = await buildSchema({
//         resolvers: [UserResolver, InvestmentResolver, WithDrawalResolver],
//         emitSchemaFile: true,
//         validate: false,
//     })

//     const server = new ApolloServer({
//         schema,
//         plugins: [ ApolloServerPluginLandingPageGraphQLPlayground ]
//     })

//     mongoose.connect(process.env.MONGO_URI ?? '')


//     const app = express();
//     await server.start()

//     server.applyMiddleware({ app })

//     return { app, server }

// }

(async () => {
    const appServer = new Server()
    const server = await appServer.Start()
    server.listen({ port: 3333 }, () => {
        console.log("Server is running on http://localhost:3333")
    })

})()

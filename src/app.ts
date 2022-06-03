import "reflect-metadata";
import dotenv from 'dotenv'
dotenv.config()
import Server from "./server";

(async () => {
    const appServer = new Server()
    const server = await appServer.Start()
    server.listen({ port: 3333 }, () => {
        console.log("Server is running on http://localhost:3333")
    })

})()

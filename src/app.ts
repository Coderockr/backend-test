import Koa, { Context } from "koa";
import cors from "@koa/cors";
import Router from "@koa/router";
import bodyParser from "koa-bodyparser";
import logger from "koa-logger";
import { getGraphQLParameters, processRequest, renderGraphiQL, sendResult, shouldRenderGraphiQL } from 'graphql-helix';

import { schema } from "./schema/schema";
import { getContext } from "./getContext";
import { getUser } from "./auth";

const app = new Koa();
const router = new Router();

app.use(logger());
app.use(cors());
app.use(bodyParser())

app.on('error', err => {
  // eslint-disable-next-line
  console.log('app error: ', err);
});

router.get('/', async ctx => {
  ctx.body = 'Welcome to your koa server'
})

export const setCookie = (context: Context) => (cookieName: string, token: string) => {
  const options = {
    httpOnly: true,
    path: '/'
  }

  context.cookies.set(cookieName, token, options)
}

router.all('/graphql', async ctx => {
  const { user } = await getUser(ctx.header.authorization)

  const request = {
    body: ctx.request.body,
    headers: ctx.req.headers,
    method: ctx.request.method,
    query: ctx.request.query,
  };

  if (shouldRenderGraphiQL(request)) {
    ctx.body = renderGraphiQL({
      subscriptionsEndpoint: '/graphql',
    });
  } else {
    const { operationName, query, variables } = getGraphQLParameters(request);

    const result = await processRequest({
      operationName,
      query,
      variables,
      request,
      schema,
      contextFactory: () => {
        return getContext({
          req: request,
          user,
          koaContext: ctx,
          setCookie: setCookie(ctx)
        })
      }
    });

    ctx.respond = false;
    sendResult(result, ctx.res);
  }
});


app.use(router.routes()).use(router.allowedMethods());

export default app;

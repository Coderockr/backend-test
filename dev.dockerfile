FROM node:14

ENV NODE_ENV=development

RUN mkdir -p /home/node/app/node_modules && chown -R node:node /home/node/app

WORKDIR /home/node/app

COPY package*.json ./

COPY --chown=node:node . .

RUN npm install

COPY . .

USER node

EXPOSE 8000
EXPOSE 8001
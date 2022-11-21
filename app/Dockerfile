FROM node:lts-alpine

RUN apk add --no-cache bash

RUN npm install -g @nestjs/cli

ENV TZ="America/Sao_Paulo"

USER node

WORKDIR /home/node/app
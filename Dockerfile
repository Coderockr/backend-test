FROM node:14-alpine
WORKDIR /app
COPY package.json /app
RUN yarn
COPY . /app
RUN yarn ts-build
CMD yarn start
EXPOSE 3333
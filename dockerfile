FROM node:alpine3.15

WORKDIR /USR/APP/

COPY ./expressApi/package*.json ./expressApi/

RUN npm install --prefix ./expressApi
RUN npm install --prefix express ./expressApi


RUN docker-composer build
RUN docker-composer up

COPY . ./


EXPOSE 3000

CMD ["npm","start","--prefix","./expressApi"]
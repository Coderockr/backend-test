FROM node:alpine3.15

WORKDIR /USR/APP/

COPY ./expressApi/package*.json ./expressApi/

RUN npm install --prefix ./expressApi
RUN npm install --prefix express ./expressApi


COPY . ./

EXPOSE 3000
EXPOSE 3030

CMD ["npm","start","--prefix","./expressApi"]
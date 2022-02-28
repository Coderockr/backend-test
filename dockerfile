FROM node:alpine3.15

WORKDIR /USR/APP/

COPY ./expressapi/package*.json ./expressapi/
RUN npm install --prefix ./expressapi
RUN npm install --prefix ./expressapi express

#COPY ./reactfront/package*.json ./reactfront/
#RUN npm install --prefix ./reactfront
#RUN npm i --prefix ./reactfront create-react-app
#RUN npx create-react-app reactfront
#RUN rm -rf reactfront/public/
#RUN rm -rf reactfront/src/
#RUN rm -rf reactfront/.gitignore
#RUN rm -rf reactfront/README.md
#RUN npm install --prefix ./reactfront axios
#RUN export NODE_OPTIONS=--openssl-legacy-provider

COPY . ./

EXPOSE 3000
EXPOSE 3030
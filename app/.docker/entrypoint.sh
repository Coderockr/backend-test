#!/bin/bash

npm install
npm run build
npx prisma migrate deploy
TZ="America/Sao_Paulo" npm run start:dev
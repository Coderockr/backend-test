#!/bin/bash

npm install
npm run build
npx prisma migrate
npm run start:dev
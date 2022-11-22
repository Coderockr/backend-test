# App Investiments - Backend Test

> App for investments, created for show my skills on backend.

### 💻 Node.js - Version

<p align="center">
  <a href="#%EF%B8%8F-tecnologies">Tecnologies</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
  <a href="#-project">Project</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
  <a href="#-how-Start">How Start</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
  <a href="#-Docs">Docs</a>
  <a href="#-license">License</a>&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
  <a href="#atom_symbol-follow-me">Follow Me!</a>
</p>

![image](https://user-images.githubusercontent.com/50564121/163297818-11a4d919-58a0-4b2b-b65e-110d3aa55f72.png)

## ⚒️ Tecnologies

- [Nestjs](https://docs.nestjs.com/)
- [Jest](https://jestjs.io/)
- [PrismaORM](https://www.prisma.io/)
- [Typescript](https://www.typescriptlang.org/)
- [Docker Engine](https://docs.docker.com/get-started/)

## 💻 Project

This project as developed with purpose to show my programming skills to a backend test

## 🚀 How start

- Open project folder (app) `cd ./app`
- Run docker build `docker-compose build`
- Run docker project using `docker-compose up`
- Note: To open bash you can use `docker-compose exec app bash` - Docker Bash is necessary to run [PrismaORM](https://www.prisma.io/) migrations. You need open bash and run migrations using command `npx prisma migrate dev`.
- Note: if you use linux -> in terminal run `chmod +x .docker/entrypoint.sh` to get permission of execute.
- Note: (windows) if you have error "standard_init_linux.go:228: exec user process caused: no such file or directory. Change EOL in Dockerfile, entrypoint.sh to LF.
- [Optional]: You can also run this application whitout docker. If you try it open app folder and run 1. `yarn` 2. `npx prisma migrate dev` 3. `yarn start:dev`
- [Env]: Check if `.env` is configured, you need create a new file with `.env` name and copy and paste `.env.example` informations to `.env` file and configure based on you local dependencies

"

## 🕸️ Test in WEB

Open Browser in [`localhost:3000`](http://localhost:3000).

# 📚 Docs

- OpenApi 3.0 Using Swagger
- Check Documentation Here [AppInvestments](localhost:3000/api)
- Note: you need run project to view documentation

## 📝 License

This project use a <a href="./LICENSE"> MIT </a> License

## :atom_symbol: Follow me!

- Instagram: https://www.instagram.com/sylvn001/
- Twitch: https://www.twitch.tv/sylvn001
- GitHub: https://github.com/Sylvn001

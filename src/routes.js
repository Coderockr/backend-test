const { Router } = require('express')
const multer = require('multer')
    //const multerConfig = require('../config/multer')
const cors = require('cors')
const UserController = require('./controller/UserController')
const EventController = require('./controller/EventsController')
const UserEventController = require('./controller/UserEventController')

const upload = multer({
    dest: '../tmp/uploads'
})

const routes = Router()

//Rotas para Usuários
routes.post('/user/create', cors(), upload.single('File'), UserController.insertUser)
routes.get('/user/:page', cors(), UserController.index)

//Rota para autenticação de usuario
routes.post('/user/auth', cors(), UserController.autenticate)

//Rotas principal e para busca de eventos
routes.get('/index/:page', cors(), EventController.index)
routes.post('/event/create', cors(), EventController.insert)
routes.get('/event/index/:id', cors(), EventController.indexDetails)
routes.get('/event/region/:region', cors(), EventController.showRegion)
routes.get('/event/date/:date', cors(), EventController.showDate)

//Rotas para usuarios ligados a eventos
routes.post('/userEvent/:userId/:eventId', UserEventController.insert)
routes.get('/userEvent', UserEventController.index)



module.exports = routes
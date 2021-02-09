const { Router } = require('express')
const multer = require('multer')
    //const multerConfig = require('../config/multer')
const UserController = require('./controller/UserController')
const EventController = require('./controller/EventsController')
const UserEventController = require('./controller/UserEventController')

const upload = multer({
    dest: '../tmp/uploads'
})

const routes = Router()

//Rotas para Usuários
routes.post('/user/create', upload.single('File'), UserController.insertUser)
routes.get('/user/:page', UserController.index)

//Rotas para eventos
routes.post('/event/create', EventController.insert)
routes.get('/event/:page', EventController.index)
routes.get('/event/index/:id', EventController.indexDetails)
routes.get('/event/region/:region', EventController.showRegion)
routes.get('/event/date/:date', EventController.showDate)

//Rotas para usuarios ligados a eventos
routes.post('/userEvent/:userId/:eventId', UserEventController.insert)
routes.get('/userEvent', UserEventController.index)



module.exports = routes
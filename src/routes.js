const { Router } = require('express')
const multer = require('multer')
    //const multerConfig = require('../config/multer')
const UserController = require('./controller/UserController')
const EventController = require('./controller/EventsController')

const upload = multer({
    dest: '../tmp/uploads'
})

const routes = Router()

routes.post('/user/create', upload.single('Profile_Picture'), UserController.insertUser)
routes.get('/user/:page', UserController.index)
routes.post('/event/create', EventController.insert)
routes.get('/event/:page', EventController.index)
routes.get('/event/index/:id', EventController.indexDetails)
routes.get('/event/region/:region', EventController.showRegion)
routes.get('/event/date/:date', EventController.showDate)




module.exports = routes
const express = require('express')
const routes = require('./routes')
const path = require('path')
const connection = require('../config/connection')
const dotenv = require('dotenv')

dotenv.config()

const User = require('./models/User')
const Event = require('./models/Event')
const UserEvent = require('./models/UserEvent')


const app = express()

User.init(connection)
Event.init(connection)
UserEvent.init(connection)


app.use(express.json())
app.use(express.urlencoded({ extended: true }))
app.use('/files', express.static(path.resolve(__dirname, '..', 'tmp', 'uploads')))

app.use(routes)

app.listen(3000)
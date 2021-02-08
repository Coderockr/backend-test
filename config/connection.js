const { Sequelize } = require('sequelize')
const dotenv = require('dotenv')

dotenv.config()

const sequelize = new Sequelize(`postgres://${process.env.USER_BD}:${process.env.PASSWORD}@ziggy.db.elephantsql.com:5432/${process.env.DATABASE}`, {
    dialect: 'postgres'
})

module.exports = sequelize
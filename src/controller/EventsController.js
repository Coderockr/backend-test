const { options } = require('../models/Event')
const Events = require('../models/Event')


module.exports = {
    async insert(req, res) {
        const {
            Name,
            Description,
            Region,
            Date
        } = req.body

        const data = {
            Name,
            Description,
            Region,
            Date
        }

        try {
            const result = await Events.create(data)
            res.send(result)
        } catch (error) {
            res.send(error)
        }

    },

    async index(req, res) {
        const pg = req.params.page

        const options = {
            page: pg,
            paginate: 10
        }

        try {
            const result = await Events.paginate(options)
            res.send(result)
        } catch (error) {
            console.log(error)
            res.send(500)
        }

    },
    async indexDetails(req, res) {


        try {
            const result = await Events.findOne({
                where: { id: req.params.id }
            })
            res.send(result)
        } catch (error) {
            console.log(error)
            res.send(500)
        }
    },

    async showRegion(req, res) {
        const region = req.params.region
        try {
            const result = await Events.findAll({
                where: { Region: region }
            })
            res.send(result)
        } catch (error) {
            console.log(error)
            res.send(500)
        }
    },

    async showDate(req, res) {

        try {
            const result = await Events.findAll({
                where: { Date: req.params.date }
            })
            res.send(result)
        } catch (error) {
            console.log(error)
            res.send(500)
        }
    }
}
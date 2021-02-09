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
            res.status(400).send('Event not inserted!')
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
            res.status(500).send('Internal error!')
        }

    },
    async indexDetails(req, res) {


        try {
            const result = await Events.findOne({
                where: { id: req.params.id }
            })
            res.send(result)
        } catch (error) {
            res.status(400).send('Invalid Event!')
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
            res.status(400).send('Not events for this region!')
        }
    },

    async showDate(req, res) {

        try {
            const result = await Events.findAll({
                where: { Date: req.params.date }
            })
            res.send(result)
        } catch (error) {
            res.status(400).send('Data not found!')
        }
    }
}
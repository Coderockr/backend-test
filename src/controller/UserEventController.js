const UserEvent = require('../models/UserEvent')


module.exports = {
    async insert(req, res) {
        const idUser = req.params.userId
        const idEvent = req.params.eventId

        const data = {
            user_id: idUser,
            event_id: idEvent
        }

        try {
            const result = await UserEvent.create(data)
            res.send(result)
        } catch (error) {
            res.status(500).json({ erro: 'Not Inserted!' })
            console.log(error)

        }

    },

    async index(req, res) {
        try {
            const result = await UserEvent.findAll()
            res.send(result)
        } catch (error) {
            res.status(500).json({ erro: 'Not found' })
            console.log(error)
        }
    }
}
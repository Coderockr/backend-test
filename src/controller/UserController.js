const User = require('../models/User')

module.exports = {
    async insertUser(req, res) {
        const {
            Name,
            Email,
            Password,
            Bio,
            City,
            State
        } = req.body

        const key = req.file.key

        const Profile_Picture = `${process.env.APP_URL}/files/${key}`

        const data = {
            Name,
            Email,
            Password,
            Bio,
            City,
            State,
            Profile_Picture
        }

        try {
            const result = await User.create(data)
            res.json(result)
        } catch (error) {
            res.send(500)
            res.send('User not inserted!')

        }
    },

    async index(req, res) {
        const pg = req.params.page

        const options = {
            page: pg,
            paginate: 10
        }

        try {
            const result = await User.paginate(options)
            res.send(result)
        } catch (error) {
            res.send(error)
        }

    }
}
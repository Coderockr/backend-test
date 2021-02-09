const User = require('../models/User')
const jwt = require('jsonwebtoken')
const authConfig = require('../../config/auth.json')

module.exports = {
    async insertUser(req, res) {
        const {
            Name,
            Email,
            Password,
            Bio,
            City,
            State,
            File
        } = req.body

        const filename = req.file.filename


        const Profile_Picture = `${process.env.APP_URL}/files/${filename}`

        const data = {
            Name,
            Email,
            Password,
            Bio,
            City,
            State,
            Profile_Picture,
            File
        }

        try {
            const result = await User.create(data)
            const dataUser = {
                Name: result.Name,
                Email: result.Email,
                Bio: result.Bio
            }
            res.json(dataUser)
        } catch (error) {
            res.status(400).send('User not inserted!')

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
            res.status(400).send('Internal error')
        }

    },

    async autenticate(req, res) {
        const {
            Email,
            Password
        } = req.body

        const user = await User.findOne({
            where: { Email }
        })

        if (!user) {
            res.status(400).send({ error: 'User not Found!' })
        }

        if (user.Password !== Password)
            return res.status(400).send({ error: 'Invalid Password!' })

        const token = jwt.sign({ id: user.id }, authConfig.secret, {
            expiresIn: 86400,
        })


        const dataUser = {
            id: user.id,
            Name: user.Name,
            Email: user.Email
        }

        res.send({ dataUser, token })
    }
}
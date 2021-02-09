const multer = require('multer')
const path = require('path')

module.exports = {
    local: multer.diskStorage({
        destination: (req, file, cb) => {
            cb(null, path.resolve(__dirname, '..', '..', 'tmp', 'uploads'))
        },
        filename: (req, file, cb) => {
            file.key = `${Date.now()}-${file.originalname}`
            cb(null, file.key)
        }
    }),
}
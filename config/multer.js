const multer = require('multer')
const path = require('path')

const storageTypes = {
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

module.exports = {
    dest: path.resolve(__dirname, '..', '..', 'tmp', 'uploads'),
    storage: storageTypes['local'],
    limits: {
        fileSize: 2 * 1024 * 1024,
    },
    fileFilter: (req, file, cb) => {
        const alowedMimes = [
            'image/jpeg',
            'image/pjpeg',
            'image/png',
            'image/gif'
        ]

        if (alowedMimes.includes(file.mimetype)) {
            cb(null, true)
        } else {
            cb(new Error("Invalid file type!"))
        }
    }
}
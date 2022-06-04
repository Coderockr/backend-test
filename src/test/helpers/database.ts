import mongoose from "mongoose"

const mongooseOptions = {
    useNewUrlParser: true,
    connectTimeoutMS: 10000,
}

export const connectMongoose = async () => {
    await mongoose.connect(process.env.MONGO_URI ?? '', {
        ...mongooseOptions
    })
}

export const clearDatabase = async () => {
    await mongoose.connection.db.dropDatabase()
}

export const disconnectMongoose = async () => {
    await mongoose.disconnect()
}
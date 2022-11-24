import mongoose from 'mongoose';
import dotenv from "dotenv";

dotenv.config();

export const connectDB = () => {
  mongoose.connect(process.env.MONGO_URI);

  const db = mongoose.connection;
  db.on("error", console.error.bind(console, "connection error:"));
  db.once("open", () => console.log("Database connected ✅"));
}

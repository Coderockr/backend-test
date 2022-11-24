import { connectDB }from './database/database';
import dotenv from "dotenv";
import { createServer } from "http";
import app from './app';

(async () => {
  dotenv.config();
  try {
    connectDB();
  } catch (error) {
    console.error("Unable to connect to database");
    process.exit(1);
  }

  const server = createServer(app.callback());

  server.listen(process.env.PORT, () => {
    console.log(`Server running at ${process.env.PORT} 🚀`)

  });
})();

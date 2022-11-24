const env = process.env;

export const database = env.MONGO_URI;
export const jwtSecret = env.JWT_SECRET;

export const auth = {
  user: env.SMTP_AUTH_USER,
  pass: env.SMTP_AUTH_PASS
}
export const service = env.EMAIL_SERVICE

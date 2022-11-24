import nodemailer from 'nodemailer';
import nodemailerMjmlPlugin from "nodemailer-mjml";
import { auth, service } from '../config';
import { join } from 'path';

export async function sendEmail(username: string, email: string) {
  const transporter = nodemailer.createTransport({
    service: service,
    auth: auth
  });

  transporter.use('compile', nodemailerMjmlPlugin({ templateFolder: join(__dirname, "templates") }))

  await transporter.sendMail({
    from: "zzleandrobritozz@gmail.com",
    subject: "Thank you for registering into our app",
    to: email,
    templateName: "register",
    templateData: {
      username: username
    }
  });
}


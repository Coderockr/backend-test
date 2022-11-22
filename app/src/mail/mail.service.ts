import { MailerService } from '@nestjs-modules/mailer';
import { Injectable } from '@nestjs/common';

@Injectable()
export class MailService {
  constructor(private mailerService: MailerService) {}

  async sendMailTo(email: string, name: string, message: string) {
    await this.mailerService.sendMail({
      to: email,
      subject: 'Investments - your investment is paying off',
      template: 'investment',
      context: {
        message,
        name,
      },
    });
  }
}

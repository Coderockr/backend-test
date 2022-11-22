import { ValidationPipe } from '@nestjs/common';
import { NestFactory } from '@nestjs/core';
import { NestExpressApplication } from '@nestjs/platform-express';
import { DocumentBuilder, SwaggerModule } from '@nestjs/swagger';
import { join } from 'path';
import { AppModule } from './app.module';

async function bootstrap() {
  const app = await NestFactory.create<NestExpressApplication>(AppModule);

  /*To Email Services */
  app.setBaseViewsDir(join(__dirname, '..', 'views'));
  app.setViewEngine('hbs');

  /*Validations Pipe -- Class Validator */
  app.useGlobalPipes(
    new ValidationPipe({
      whitelist: true,
      forbidNonWhitelisted: true,
      transform: true,
    }),
  );

  /*Swagger configs */
  const config = new DocumentBuilder()
    .setTitle('Investiments Documentation')
    .setDescription('Investiments APP - Open Api 3.0')
    .setVersion('1.0')
    .addTag('Investiments')
    .build();
  const document = SwaggerModule.createDocument(app, config);

  SwaggerModule.setup('api', app, document);

  /*Run Server */
  await app.listen(3000);
}
bootstrap();

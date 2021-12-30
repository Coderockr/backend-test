import { prisma } from "../../../database/prismaClient";
import { compare } from "bcrypt";
import { sign } from "jsonwebtoken";

interface IAuthenticate {
  email: string;
  password: string;
}

export class AuthenticateUseCase {
  async execute({ email, password }: IAuthenticate) {

    const investor = await prisma.investor.findFirst({
      where: {
        email,
      },
    });

    if (!investor) {
      throw new Error("Email or password invalid!");
    }

    const passwordMatch = await compare(password, investor.password);

    if (!passwordMatch) {
      throw new Error("Username or password invalid!");
    }

    const token = sign({ email }, "019acc25a4e242bb55ad489832ada12d", {
      subject: investor.id,
      expiresIn: "1d",
    });

    return token;
  }
}

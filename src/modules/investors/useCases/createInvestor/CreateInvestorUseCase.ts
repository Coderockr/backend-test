import { prisma } from "../../../../database/prismaClient";
import { hash } from "bcrypt";

interface ICreateInvestor {
  name: string;
  email: string;
  password: string;
}

export class CreateInvestorUseCase {
  async execute({ name, email, password }: ICreateInvestor) {
    const investorExist = await prisma.investor.findFirst({
      where: {
        email: {
          equals: email,
          mode: "insensitive",
        },
      },
    });

    if (investorExist) {
      throw new Error("Investor already exists");
    }

    const hashPassword = await hash(password, 10);

    const investor = await prisma.investor.create({
      data: {
        name,
        email,
        password: hashPassword,
      },
    });

    return investor;
  }
}

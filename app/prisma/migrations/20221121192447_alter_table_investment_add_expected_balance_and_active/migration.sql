-- CreateTable
CREATE TABLE "owners" (
    "id" SERIAL NOT NULL,
    "name" VARCHAR(60) NOT NULL,
    "email" VARCHAR(255) NOT NULL,

    CONSTRAINT "owners_pkey" PRIMARY KEY ("id")
);

-- CreateTable
CREATE TABLE "investiments" (
    "id" SERIAL NOT NULL,
    "owner_id" INTEGER NOT NULL,
    "creation_date" TIMESTAMPTZ NOT NULL,
    "initial_amount" MONEY NOT NULL,
    "expected_balance" MONEY NOT NULL,
    "amount" MONEY NOT NULL,
    "active" BOOLEAN NOT NULL DEFAULT true,

    CONSTRAINT "investiments_pkey" PRIMARY KEY ("id")
);

-- AddForeignKey
ALTER TABLE "investiments" ADD CONSTRAINT "investiments_owner_id_fkey" FOREIGN KEY ("owner_id") REFERENCES "owners"("id") ON DELETE RESTRICT ON UPDATE CASCADE;

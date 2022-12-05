-- CreateTable
CREATE TABLE "investors" (
    "id" TEXT NOT NULL,
    "name" TEXT NOT NULL,
    "email" TEXT NOT NULL,
    "password" TEXT NOT NULL,

    CONSTRAINT "investors_pkey" PRIMARY KEY ("id")
);

-- CreateTable
CREATE TABLE "investiments" (
    "id" TEXT NOT NULL,
    "id_investor" TEXT NOT NULL,
    "created_at" TIMESTAMP(3) NOT NULL DEFAULT CURRENT_TIMESTAMP,
    "amount" DOUBLE PRECISION NOT NULL,
    "withdraw_at" TIMESTAMP(3),
    "withdraw_value" DOUBLE PRECISION,

    CONSTRAINT "investiments_pkey" PRIMARY KEY ("id")
);

-- CreateIndex
CREATE UNIQUE INDEX "investors_email_key" ON "investors"("email");

-- AddForeignKey
ALTER TABLE "investiments" ADD CONSTRAINT "investiments_id_investor_fkey" FOREIGN KEY ("id_investor") REFERENCES "investors"("id") ON DELETE RESTRICT ON UPDATE CASCADE;

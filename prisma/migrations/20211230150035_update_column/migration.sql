/*
  Warnings:

  - You are about to drop the column `amount` on the `investiments` table. All the data in the column will be lost.
  - Added the required column `capital` to the `investiments` table without a default value. This is not possible if the table is not empty.

*/
-- AlterTable
ALTER TABLE "investiments" DROP COLUMN "amount",
ADD COLUMN     "capital" DOUBLE PRECISION NOT NULL;

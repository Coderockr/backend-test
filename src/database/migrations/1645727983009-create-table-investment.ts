import {
  MigrationInterface,
  QueryRunner,
} from 'typeorm';

export class createTableInvestment1645727983009 implements MigrationInterface {
  public async up(queryRunner: QueryRunner): Promise<void> {
    queryRunner.query(
      "CREATE TABLE `investments` (`id` int NOT NULL AUTO_INCREMENT, `investor_id` int NOT NULL, `reference_date` date NOT NULL, `amount` decimal(15, 2) NOT NULL, `created_at` timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6), `updated_at` timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6), PRIMARY KEY (`id`)) ENGINE=InnoDB",
    );

    await queryRunner.query(
      'ALTER TABLE `investments` ADD CONSTRAINT `FK_81fb5c6d45d1a030d6c634abcfc` FOREIGN KEY (`investor_id`) REFERENCES `investors`(`id`) ON DELETE NO ACTION ON UPDATE NO ACTION',
    );
  }

  public async down(queryRunner: QueryRunner): Promise<void> {
    await queryRunner.query(
      'ALTER TABLE `investments` DROP FOREIGN KEY `FK_81fb5c6d45d1a030d6c634abcfc`',
    );
    await queryRunner.query('DROP TABLE `investments`');
  }
}

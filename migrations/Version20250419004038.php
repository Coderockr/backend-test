<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250419004038 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE propietario (id INT AUTO_INCREMENT NOT NULL, investimento_id INT DEFAULT NULL, nome VARCHAR(255) NOT NULL, INDEX IDX_BF0B38F030121016 (investimento_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE propietario ADD CONSTRAINT FK_BF0B38F030121016 FOREIGN KEY (investimento_id) REFERENCES investimento (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE investimento DROP propietario
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE propietario DROP FOREIGN KEY FK_BF0B38F030121016
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE propietario
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE investimento ADD propietario VARCHAR(255) NOT NULL
        SQL);
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250419021557 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE usuario (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE investimento ADD propietario_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE investimento ADD CONSTRAINT FK_8D61B2F953C8D32C FOREIGN KEY (propietario_id) REFERENCES propietario (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8D61B2F953C8D32C ON investimento (propietario_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE propietario DROP FOREIGN KEY FK_BF0B38F030121016
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_BF0B38F030121016 ON propietario
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE propietario DROP investimento_id
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP TABLE usuario
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE investimento DROP FOREIGN KEY FK_8D61B2F953C8D32C
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_8D61B2F953C8D32C ON investimento
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE investimento DROP propietario_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE propietario ADD investimento_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE propietario ADD CONSTRAINT FK_BF0B38F030121016 FOREIGN KEY (investimento_id) REFERENCES investimento (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_BF0B38F030121016 ON propietario (investimento_id)
        SQL);
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240107150940 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE investment DROP FOREIGN KEY FK_43CA0AD6229E70A7');
        $this->addSql('DROP INDEX IDX_43CA0AD6229E70A7 ON investment');
        $this->addSql('ALTER TABLE investment DROP movement_id');
        $this->addSql('ALTER TABLE movement ADD investment_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE movement ADD CONSTRAINT FK_F4DD95F76E1B4FD5 FOREIGN KEY (investment_id) REFERENCES investment (id)');
        $this->addSql('CREATE INDEX IDX_F4DD95F76E1B4FD5 ON movement (investment_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE investment ADD movement_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE investment ADD CONSTRAINT FK_43CA0AD6229E70A7 FOREIGN KEY (movement_id) REFERENCES movement (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_43CA0AD6229E70A7 ON investment (movement_id)');
        $this->addSql('ALTER TABLE movement DROP FOREIGN KEY FK_F4DD95F76E1B4FD5');
        $this->addSql('DROP INDEX IDX_F4DD95F76E1B4FD5 ON movement');
        $this->addSql('ALTER TABLE movement DROP investment_id');
    }
}

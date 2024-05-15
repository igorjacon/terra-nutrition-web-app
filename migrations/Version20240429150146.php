<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240429150146 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer ADD professional_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT FK_81398E09DB77003 FOREIGN KEY (professional_id) REFERENCES professional (id) ON DELETE SET NULL');
        $this->addSql('CREATE INDEX IDX_81398E09DB77003 ON customer (professional_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer DROP FOREIGN KEY FK_81398E09DB77003');
        $this->addSql('DROP INDEX IDX_81398E09DB77003 ON customer');
        $this->addSql('ALTER TABLE customer DROP professional_id');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240516115711 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE meal_plan ADD professional_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE meal_plan ADD CONSTRAINT FK_C7848889DB77003 FOREIGN KEY (professional_id) REFERENCES professional (id)');
        $this->addSql('CREATE INDEX IDX_C7848889DB77003 ON meal_plan (professional_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE meal_plan DROP FOREIGN KEY FK_C7848889DB77003');
        $this->addSql('DROP INDEX IDX_C7848889DB77003 ON meal_plan');
        $this->addSql('ALTER TABLE meal_plan DROP professional_id');
    }
}

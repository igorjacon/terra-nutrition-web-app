<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240523051753 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE meal_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, position INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE meal ADD type_id INT DEFAULT NULL, DROP type');
        $this->addSql('ALTER TABLE meal ADD CONSTRAINT FK_9EF68E9CC54C8C93 FOREIGN KEY (type_id) REFERENCES meal_type (id)');
        $this->addSql('CREATE INDEX IDX_9EF68E9CC54C8C93 ON meal (type_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE meal DROP FOREIGN KEY FK_9EF68E9CC54C8C93');
        $this->addSql('DROP TABLE meal_type');
        $this->addSql('DROP INDEX IDX_9EF68E9CC54C8C93 ON meal');
        $this->addSql('ALTER TABLE meal ADD type VARCHAR(255) NOT NULL, DROP type_id');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240331143325 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE location ADD line_one VARCHAR(255) NOT NULL, ADD line_two VARCHAR(255) DEFAULT NULL, ADD city VARCHAR(255) DEFAULT NULL, ADD zip_code VARCHAR(10) DEFAULT NULL, ADD state VARCHAR(255) DEFAULT NULL, ADD country VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user ADD line_one VARCHAR(255) NOT NULL, ADD line_two VARCHAR(255) DEFAULT NULL, ADD city VARCHAR(255) DEFAULT NULL, ADD zip_code VARCHAR(10) DEFAULT NULL, ADD state VARCHAR(255) DEFAULT NULL, ADD country VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE location DROP line_one, DROP line_two, DROP city, DROP zip_code, DROP state, DROP country');
        $this->addSql('ALTER TABLE user DROP line_one, DROP line_two, DROP city, DROP zip_code, DROP state, DROP country');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240331144644 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
//        $this->addSql('ALTER TABLE location CHANGE line_one line_one VARCHAR(255) DEFAULT NULL, CHANGE country country VARCHAR(255) DEFAULT NULL');
//        $this->addSql('ALTER TABLE user CHANGE line_one line_one VARCHAR(255) DEFAULT NULL, CHANGE country country VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
//        $this->addSql('ALTER TABLE location CHANGE line_one line_one VARCHAR(255) NOT NULL, CHANGE country country VARCHAR(255) NOT NULL');
//        $this->addSql('ALTER TABLE user CHANGE line_one line_one VARCHAR(255) NOT NULL, CHANGE country country VARCHAR(255) NOT NULL');
    }
}

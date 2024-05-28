<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240528113721 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer_measurement ADD right_wrist VARCHAR(255) DEFAULT NULL, ADD left_wrist VARCHAR(255) DEFAULT NULL, DROP right_fist, DROP left_fist');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer_measurement ADD right_fist VARCHAR(255) DEFAULT NULL, ADD left_fist VARCHAR(255) DEFAULT NULL, DROP right_wrist, DROP left_wrist');
    }
}

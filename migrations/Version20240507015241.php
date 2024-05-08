<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240507015241 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE food_item (food_key VARCHAR(10) NOT NULL, profile_id INT NOT NULL, derivation VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, sampling_details LONGTEXT DEFAULT NULL, nitrogen_factor DOUBLE PRECISION DEFAULT NULL, fat_factor DOUBLE PRECISION DEFAULT NULL, specific_gravity DOUBLE PRECISION DEFAULT NULL, analysed_portion VARCHAR(255) DEFAULT NULL, unanalysed_portion VARCHAR(255) DEFAULT NULL, classification INT NOT NULL, classification_name VARCHAR(255) DEFAULT NULL, PRIMARY KEY(food_key)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE food_item');
    }
}

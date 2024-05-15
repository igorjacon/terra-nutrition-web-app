<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240401131600 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
//        $this->addSql('CREATE TABLE address (id INT AUTO_INCREMENT NOT NULL, line_one VARCHAR(255) DEFAULT NULL, line_two VARCHAR(255) DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, zip_code VARCHAR(10) DEFAULT NULL, state VARCHAR(255) DEFAULT NULL, country VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
//        $this->addSql('CREATE TABLE phone (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, prefix VARCHAR(5) DEFAULT NULL, number VARCHAR(12) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, INDEX IDX_444F97DDA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
//        $this->addSql('ALTER TABLE phone ADD CONSTRAINT FK_444F97DDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
//        $this->addSql('ALTER TABLE location ADD phone_id INT DEFAULT NULL, ADD address_id INT DEFAULT NULL, DROP phone_number, DROP line_one, DROP line_two, DROP city, DROP zip_code, DROP state, DROP country');
//        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB3B7323CB FOREIGN KEY (phone_id) REFERENCES phone (id)');
//        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CBF5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
//        $this->addSql('CREATE UNIQUE INDEX UNIQ_5E9E89CB3B7323CB ON location (phone_id)');
//        $this->addSql('CREATE UNIQUE INDEX UNIQ_5E9E89CBF5B7AF75 ON location (address_id)');
//        $this->addSql('ALTER TABLE user ADD address_id INT DEFAULT NULL, DROP phone_number, DROP line_one, DROP line_two, DROP city, DROP zip_code, DROP state, DROP country');
//        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id)');
//        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F5B7AF75 ON user (address_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
//        $this->addSql('ALTER TABLE location DROP FOREIGN KEY FK_5E9E89CBF5B7AF75');
//        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649F5B7AF75');
//        $this->addSql('ALTER TABLE location DROP FOREIGN KEY FK_5E9E89CB3B7323CB');
//        $this->addSql('ALTER TABLE phone DROP FOREIGN KEY FK_444F97DDA76ED395');
//        $this->addSql('DROP TABLE address');
//        $this->addSql('DROP TABLE phone');
//        $this->addSql('DROP INDEX UNIQ_8D93D649F5B7AF75 ON user');
//        $this->addSql('ALTER TABLE user ADD phone_number VARCHAR(18) DEFAULT NULL, ADD line_one VARCHAR(255) DEFAULT NULL, ADD line_two VARCHAR(255) DEFAULT NULL, ADD city VARCHAR(255) DEFAULT NULL, ADD zip_code VARCHAR(10) DEFAULT NULL, ADD state VARCHAR(255) DEFAULT NULL, ADD country VARCHAR(255) DEFAULT NULL, DROP address_id');
//        $this->addSql('DROP INDEX UNIQ_5E9E89CB3B7323CB ON location');
//        $this->addSql('DROP INDEX UNIQ_5E9E89CBF5B7AF75 ON location');
//        $this->addSql('ALTER TABLE location ADD phone_number VARCHAR(18) DEFAULT NULL, ADD line_one VARCHAR(255) DEFAULT NULL, ADD line_two VARCHAR(255) DEFAULT NULL, ADD city VARCHAR(255) DEFAULT NULL, ADD zip_code VARCHAR(10) DEFAULT NULL, ADD state VARCHAR(255) DEFAULT NULL, ADD country VARCHAR(255) DEFAULT NULL, DROP phone_id, DROP address_id');
    }
}

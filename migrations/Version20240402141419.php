<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240402141419 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
//        $this->addSql('CREATE TABLE customer (id INT NOT NULL, age INT DEFAULT NULL, height VARCHAR(10) DEFAULT NULL, weight VARCHAR(10) DEFAULT NULL, dob VARCHAR(255) NOT NULL, goal_weight VARCHAR(255) DEFAULT NULL, occupation VARCHAR(255) DEFAULT NULL, dietary_preference VARCHAR(255) DEFAULT NULL, goals VARCHAR(255) DEFAULT NULL, reason_seek_professional LONGTEXT DEFAULT NULL, curr_exercise_routine VARCHAR(255) DEFAULT NULL, medical_info LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
//        $this->addSql('CREATE TABLE measurement (id INT AUTO_INCREMENT NOT NULL, customer_id INT DEFAULT NULL, description LONGTEXT DEFAULT NULL, height VARCHAR(255) DEFAULT NULL, curr_weight VARCHAR(255) DEFAULT NULL, ideal_weight VARCHAR(255) DEFAULT NULL, right_arm_relax VARCHAR(255) DEFAULT NULL, left_arm_relax VARCHAR(255) DEFAULT NULL, right_arm_contracted VARCHAR(255) DEFAULT NULL, left_arm_contracted VARCHAR(255) DEFAULT NULL, right_forearm VARCHAR(255) DEFAULT NULL, left_forearm VARCHAR(255) DEFAULT NULL, right_fist VARCHAR(255) DEFAULT NULL, left_fist VARCHAR(255) DEFAULT NULL, neck VARCHAR(255) DEFAULT NULL, shoulder VARCHAR(255) DEFAULT NULL, breastplate VARCHAR(255) DEFAULT NULL, waist VARCHAR(255) DEFAULT NULL, abs VARCHAR(255) DEFAULT NULL, hip VARCHAR(255) DEFAULT NULL, right_calf VARCHAR(255) DEFAULT NULL, left_calf VARCHAR(255) DEFAULT NULL, right_thigh VARCHAR(255) DEFAULT NULL, left_thigh VARCHAR(255) DEFAULT NULL, right_proximal_thigh VARCHAR(255) DEFAULT NULL, left_proximal_thigh VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, INDEX IDX_2CE0D8119395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
//        $this->addSql('ALTER TABLE customer ADD CONSTRAINT FK_81398E09BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
//        $this->addSql('ALTER TABLE measurement ADD CONSTRAINT FK_2CE0D8119395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
//        $this->addSql('ALTER TABLE customer DROP FOREIGN KEY FK_81398E09BF396750');
//        $this->addSql('ALTER TABLE measurement DROP FOREIGN KEY FK_2CE0D8119395C3F3');
//        $this->addSql('DROP TABLE customer');
//        $this->addSql('DROP TABLE measurement');
    }
}

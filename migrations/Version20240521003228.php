<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240521003228 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE customer_measurement (id INT AUTO_INCREMENT NOT NULL, customer_id INT DEFAULT NULL, description LONGTEXT DEFAULT NULL, height VARCHAR(255) DEFAULT NULL, curr_weight VARCHAR(255) DEFAULT NULL, ideal_weight VARCHAR(255) DEFAULT NULL, right_arm_relax VARCHAR(255) DEFAULT NULL, left_arm_relax VARCHAR(255) DEFAULT NULL, right_arm_contracted VARCHAR(255) DEFAULT NULL, left_arm_contracted VARCHAR(255) DEFAULT NULL, right_forearm VARCHAR(255) DEFAULT NULL, left_forearm VARCHAR(255) DEFAULT NULL, right_fist VARCHAR(255) DEFAULT NULL, left_fist VARCHAR(255) DEFAULT NULL, neck VARCHAR(255) DEFAULT NULL, shoulder VARCHAR(255) DEFAULT NULL, breastplate VARCHAR(255) DEFAULT NULL, waist VARCHAR(255) DEFAULT NULL, abs VARCHAR(255) DEFAULT NULL, hip VARCHAR(255) DEFAULT NULL, right_calf VARCHAR(255) DEFAULT NULL, left_calf VARCHAR(255) DEFAULT NULL, right_thigh VARCHAR(255) DEFAULT NULL, left_thigh VARCHAR(255) DEFAULT NULL, right_proximal_thigh VARCHAR(255) DEFAULT NULL, left_proximal_thigh VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, INDEX IDX_2FABF3199395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE food_measurement (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, abbreviation VARCHAR(255) DEFAULT NULL, gram_quantity INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE customer_measurement ADD CONSTRAINT FK_2FABF3199395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE measurement DROP FOREIGN KEY FK_2CE0D8119395C3F3');
        $this->addSql('DROP TABLE measurement');
        $this->addSql('ALTER TABLE food_item_entry ADD measurement_id INT DEFAULT NULL, DROP measurement');
        $this->addSql('ALTER TABLE food_item_entry ADD CONSTRAINT FK_66C33601924EA134 FOREIGN KEY (measurement_id) REFERENCES food_measurement (id)');
        $this->addSql('CREATE INDEX IDX_66C33601924EA134 ON food_item_entry (measurement_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE food_item_entry DROP FOREIGN KEY FK_66C33601924EA134');
        $this->addSql('CREATE TABLE measurement (id INT AUTO_INCREMENT NOT NULL, customer_id INT DEFAULT NULL, description LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, height VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, curr_weight VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ideal_weight VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, right_arm_relax VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, left_arm_relax VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, right_arm_contracted VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, left_arm_contracted VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, right_forearm VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, left_forearm VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, right_fist VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, left_fist VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, neck VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, shoulder VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, breastplate VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, waist VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, abs VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, hip VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, right_calf VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, left_calf VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, right_thigh VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, left_thigh VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, right_proximal_thigh VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, left_proximal_thigh VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, created_by VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, updated_by VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_2CE0D8119395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE measurement ADD CONSTRAINT FK_2CE0D8119395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE customer_measurement DROP FOREIGN KEY FK_2FABF3199395C3F3');
        $this->addSql('DROP TABLE customer_measurement');
        $this->addSql('DROP TABLE food_measurement');
        $this->addSql('DROP INDEX IDX_66C33601924EA134 ON food_item_entry');
        $this->addSql('ALTER TABLE food_item_entry ADD measurement VARCHAR(255) DEFAULT NULL, DROP measurement_id');
    }
}

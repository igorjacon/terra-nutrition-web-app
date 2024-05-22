<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240522064240 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE recipe (id INT AUTO_INCREMENT NOT NULL, professional_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, portion INT DEFAULT NULL, instructions LONGTEXT DEFAULT NULL, INDEX IDX_DA88B137DB77003 (professional_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipe_customer (recipe_id INT NOT NULL, customer_id INT NOT NULL, INDEX IDX_F7A92B9759D8A214 (recipe_id), INDEX IDX_F7A92B979395C3F3 (customer_id), PRIMARY KEY(recipe_id, customer_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE recipe ADD CONSTRAINT FK_DA88B137DB77003 FOREIGN KEY (professional_id) REFERENCES professional (id)');
        $this->addSql('ALTER TABLE recipe_customer ADD CONSTRAINT FK_F7A92B9759D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipe_customer ADD CONSTRAINT FK_F7A92B979395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE food_item_entry ADD recipe_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE food_item_entry ADD CONSTRAINT FK_66C3360159D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id)');
        $this->addSql('CREATE INDEX IDX_66C3360159D8A214 ON food_item_entry (recipe_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE food_item_entry DROP FOREIGN KEY FK_66C3360159D8A214');
        $this->addSql('ALTER TABLE recipe DROP FOREIGN KEY FK_DA88B137DB77003');
        $this->addSql('ALTER TABLE recipe_customer DROP FOREIGN KEY FK_F7A92B9759D8A214');
        $this->addSql('ALTER TABLE recipe_customer DROP FOREIGN KEY FK_F7A92B979395C3F3');
        $this->addSql('DROP TABLE recipe');
        $this->addSql('DROP TABLE recipe_customer');
        $this->addSql('DROP INDEX IDX_66C3360159D8A214 ON food_item_entry');
        $this->addSql('ALTER TABLE food_item_entry DROP recipe_id');
    }
}

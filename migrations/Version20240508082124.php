<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240508082124 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
//        $this->addSql('CREATE TABLE meal_plan_customer (meal_plan_id INT NOT NULL, customer_id INT NOT NULL, INDEX IDX_956503FF912AB082 (meal_plan_id), INDEX IDX_956503FF9395C3F3 (customer_id), PRIMARY KEY(meal_plan_id, customer_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
//        $this->addSql('ALTER TABLE meal_plan_customer ADD CONSTRAINT FK_956503FF912AB082 FOREIGN KEY (meal_plan_id) REFERENCES meal_plan (id) ON DELETE CASCADE');
//        $this->addSql('ALTER TABLE meal_plan_customer ADD CONSTRAINT FK_956503FF9395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) ON DELETE CASCADE');
//        $this->addSql('ALTER TABLE meal_plan DROP FOREIGN KEY FK_C78488899395C3F3');
//        $this->addSql('DROP INDEX IDX_C78488899395C3F3 ON meal_plan');
//        $this->addSql('ALTER TABLE meal_plan DROP customer_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
//        $this->addSql('ALTER TABLE meal_plan_customer DROP FOREIGN KEY FK_956503FF912AB082');
//        $this->addSql('ALTER TABLE meal_plan_customer DROP FOREIGN KEY FK_956503FF9395C3F3');
//        $this->addSql('DROP TABLE meal_plan_customer');
//        $this->addSql('ALTER TABLE meal_plan ADD customer_id INT DEFAULT NULL');
//        $this->addSql('ALTER TABLE meal_plan ADD CONSTRAINT FK_C78488899395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) ON UPDATE NO ACTION ON DELETE CASCADE');
//        $this->addSql('CREATE INDEX IDX_C78488899395C3F3 ON meal_plan (customer_id)');
    }
}

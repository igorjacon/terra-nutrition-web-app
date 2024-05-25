<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240525033634 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE meal_history (id INT AUTO_INCREMENT NOT NULL, customer_id INT NOT NULL, meal_id INT NOT NULL, meal_option_id INT NOT NULL, meal_plan_id INT DEFAULT NULL, date DATE NOT NULL, time VARCHAR(255) DEFAULT NULL, INDEX IDX_5E09C2529395C3F3 (customer_id), INDEX IDX_5E09C252639666D6 (meal_id), INDEX IDX_5E09C25219AB1F64 (meal_option_id), INDEX IDX_5E09C252912AB082 (meal_plan_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE meal_history ADD CONSTRAINT FK_5E09C2529395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE meal_history ADD CONSTRAINT FK_5E09C252639666D6 FOREIGN KEY (meal_id) REFERENCES meal (id)');
        $this->addSql('ALTER TABLE meal_history ADD CONSTRAINT FK_5E09C25219AB1F64 FOREIGN KEY (meal_option_id) REFERENCES meal_option (id)');
        $this->addSql('ALTER TABLE meal_history ADD CONSTRAINT FK_5E09C252912AB082 FOREIGN KEY (meal_plan_id) REFERENCES meal_plan (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE meal_history DROP FOREIGN KEY FK_5E09C2529395C3F3');
        $this->addSql('ALTER TABLE meal_history DROP FOREIGN KEY FK_5E09C252639666D6');
        $this->addSql('ALTER TABLE meal_history DROP FOREIGN KEY FK_5E09C25219AB1F64');
        $this->addSql('ALTER TABLE meal_history DROP FOREIGN KEY FK_5E09C252912AB082');
        $this->addSql('DROP TABLE meal_history');
    }
}

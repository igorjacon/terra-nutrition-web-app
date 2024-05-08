<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240508062013 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_66C336016E12AFBD ON food_item_entry');
        $this->addSql('ALTER TABLE food_item_entry CHANGE foodKey food_key VARCHAR(10) NOT NULL');
        $this->addSql('ALTER TABLE food_item_entry ADD CONSTRAINT FK_66C336017DF74B14 FOREIGN KEY (food_key) REFERENCES food_item (food_key)');
        $this->addSql('ALTER TABLE food_item_entry ADD CONSTRAINT FK_66C3360119AB1F64 FOREIGN KEY (meal_option_id) REFERENCES meal_option (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_66C336017DF74B14 ON food_item_entry (food_key)');
        $this->addSql('ALTER TABLE meal_meal_option ADD CONSTRAINT FK_3876BB31639666D6 FOREIGN KEY (meal_id) REFERENCES meal (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE meal_meal_option ADD CONSTRAINT FK_3876BB3119AB1F64 FOREIGN KEY (meal_option_id) REFERENCES meal_option (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE meal_plan ADD CONSTRAINT FK_C78488899395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE meal_plan_meal ADD CONSTRAINT FK_354F4065912AB082 FOREIGN KEY (meal_plan_id) REFERENCES meal_plan (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE meal_plan_meal ADD CONSTRAINT FK_354F4065639666D6 FOREIGN KEY (meal_id) REFERENCES meal (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE food_item_entry DROP FOREIGN KEY FK_66C336017DF74B14');
        $this->addSql('ALTER TABLE food_item_entry DROP FOREIGN KEY FK_66C3360119AB1F64');
        $this->addSql('DROP INDEX UNIQ_66C336017DF74B14 ON food_item_entry');
        $this->addSql('ALTER TABLE food_item_entry CHANGE food_key foodKey VARCHAR(10) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_66C336016E12AFBD ON food_item_entry (foodKey)');
        $this->addSql('ALTER TABLE meal_meal_option DROP FOREIGN KEY FK_3876BB31639666D6');
        $this->addSql('ALTER TABLE meal_meal_option DROP FOREIGN KEY FK_3876BB3119AB1F64');
        $this->addSql('ALTER TABLE meal_plan DROP FOREIGN KEY FK_C78488899395C3F3');
        $this->addSql('ALTER TABLE meal_plan_meal DROP FOREIGN KEY FK_354F4065912AB082');
        $this->addSql('ALTER TABLE meal_plan_meal DROP FOREIGN KEY FK_354F4065639666D6');
    }
}

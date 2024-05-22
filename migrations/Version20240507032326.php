<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240507032326 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX `primary` ON food_item_details');
        $this->addSql('ALTER TABLE food_item_details CHANGE foodKey food_key VARCHAR(10) NOT NULL, CHANGE leadPb lead_pb DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE food_item_details ADD CONSTRAINT FK_50674BF7DF74B14 FOREIGN KEY (food_key) REFERENCES food_item (food_key) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE food_item_details ADD PRIMARY KEY (food_key)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE food_item_details DROP FOREIGN KEY FK_50674BF7DF74B14');
        $this->addSql('DROP INDEX `PRIMARY` ON food_item_details');
        $this->addSql('ALTER TABLE food_item_details CHANGE lead_pb leadPb DOUBLE PRECISION DEFAULT NULL, CHANGE food_key foodKey VARCHAR(10) NOT NULL');
        $this->addSql('ALTER TABLE food_item_details ADD PRIMARY KEY (foodKey)');
    }
}

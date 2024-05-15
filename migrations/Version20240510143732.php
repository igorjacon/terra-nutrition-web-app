<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240510143732 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE food_item_entry DROP INDEX UNIQ_66C336017DF74B14, ADD INDEX IDX_66C336017DF74B14 (food_key)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE food_item_entry DROP INDEX IDX_66C336017DF74B14, ADD UNIQUE INDEX UNIQ_66C336017DF74B14 (food_key)');
    }
}

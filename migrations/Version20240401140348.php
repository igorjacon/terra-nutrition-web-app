<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240401140348 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
//        $this->addSql('ALTER TABLE phone DROP FOREIGN KEY FK_444F97DDA76ED395');
//        $this->addSql('ALTER TABLE phone ADD flag VARCHAR(255) DEFAULT NULL');
//        $this->addSql('ALTER TABLE phone ADD CONSTRAINT FK_444F97DDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
//        $this->addSql('ALTER TABLE phone DROP FOREIGN KEY FK_444F97DDA76ED395');
//        $this->addSql('ALTER TABLE phone DROP flag');
//        $this->addSql('ALTER TABLE phone ADD CONSTRAINT FK_444F97DDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}

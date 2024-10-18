<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241016131817 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE hotels_in_bhutan (id INT AUTO_INCREMENT NOT NULL, hotel_name VARCHAR(255) NOT NULL, ratings VARCHAR(255) NOT NULL, room_type VARCHAR(255) NOT NULL, no_of_rooms VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE images ADD hotels_in_bhutan_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE images ADD CONSTRAINT FK_E01FBE6A49F55E4 FOREIGN KEY (hotels_in_bhutan_id) REFERENCES hotels_in_bhutan (id)');
        $this->addSql('CREATE INDEX IDX_E01FBE6A49F55E4 ON images (hotels_in_bhutan_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE images DROP FOREIGN KEY FK_E01FBE6A49F55E4');
        $this->addSql('DROP TABLE hotels_in_bhutan');
        $this->addSql('DROP INDEX IDX_E01FBE6A49F55E4 ON images');
        $this->addSql('ALTER TABLE images DROP hotels_in_bhutan_id');
    }
}

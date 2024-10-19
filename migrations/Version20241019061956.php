<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241019061956 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE top_destination (id INT AUTO_INCREMENT NOT NULL, destination_title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE blog CHANGE blog_description blog_description LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE tour_package CHANGE tour_overview tour_overview LONGTEXT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE top_destination');
        $this->addSql('ALTER TABLE blog CHANGE blog_description blog_description VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE tour_package CHANGE tour_overview tour_overview VARCHAR(255) NOT NULL');
    }
}

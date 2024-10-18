<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241008054735 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE itinerary (id INT AUTO_INCREMENT NOT NULL, tour_package_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, itinerary_description VARCHAR(255) NOT NULL, INDEX IDX_FF2238F61290BD60 (tour_package_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE itinerary ADD CONSTRAINT FK_FF2238F61290BD60 FOREIGN KEY (tour_package_id) REFERENCES tour_package (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE itinerary DROP FOREIGN KEY FK_FF2238F61290BD60');
        $this->addSql('DROP TABLE itinerary');
    }
}

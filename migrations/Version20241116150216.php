<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241116150216 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE travel_info (id INT AUTO_INCREMENT NOT NULL, travel_info_category_id INT DEFAULT NULL, travel_info_title VARCHAR(255) NOT NULL, travel_info_description LONGTEXT NOT NULL, INDEX IDX_D1D2FA5A19C64 (travel_info_category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE travel_info_category (id INT AUTO_INCREMENT NOT NULL, travel_info_category_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE travel_info ADD CONSTRAINT FK_D1D2FA5A19C64 FOREIGN KEY (travel_info_category_id) REFERENCES travel_info_category (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE travel_info DROP FOREIGN KEY FK_D1D2FA5A19C64');
        $this->addSql('DROP TABLE travel_info');
        $this->addSql('DROP TABLE travel_info_category');
    }
}

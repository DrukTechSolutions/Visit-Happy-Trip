<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241102121217 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tour_category (id INT AUTO_INCREMENT NOT NULL, sub_caterory_id INT DEFAULT NULL, category VARCHAR(255) NOT NULL, INDEX IDX_9CB340F2C2595B15 (sub_caterory_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tour_category ADD CONSTRAINT FK_9CB340F2C2595B15 FOREIGN KEY (sub_caterory_id) REFERENCES tour_category (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tour_category DROP FOREIGN KEY FK_9CB340F2C2595B15');
        $this->addSql('DROP TABLE tour_category');
    }
}

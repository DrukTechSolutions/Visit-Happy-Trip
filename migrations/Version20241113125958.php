<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241113125958 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tour_package DROP FOREIGN KEY FK_9E82E214C7A0C0B4');
        $this->addSql('DROP INDEX IDX_9E82E214C7A0C0B4 ON tour_package');
        $this->addSql('ALTER TABLE tour_package CHANGE tour_categories_id tour_category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tour_package ADD CONSTRAINT FK_9E82E2147210CF21 FOREIGN KEY (tour_category_id) REFERENCES tour_category (id)');
        $this->addSql('CREATE INDEX IDX_9E82E2147210CF21 ON tour_package (tour_category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tour_package DROP FOREIGN KEY FK_9E82E2147210CF21');
        $this->addSql('DROP INDEX IDX_9E82E2147210CF21 ON tour_package');
        $this->addSql('ALTER TABLE tour_package CHANGE tour_category_id tour_categories_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tour_package ADD CONSTRAINT FK_9E82E214C7A0C0B4 FOREIGN KEY (tour_categories_id) REFERENCES tour_category (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_9E82E214C7A0C0B4 ON tour_package (tour_categories_id)');
    }
}

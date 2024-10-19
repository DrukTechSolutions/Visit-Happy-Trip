<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241019062213 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE images ADD top_destination_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE images ADD CONSTRAINT FK_E01FBE6A451C64E9 FOREIGN KEY (top_destination_id) REFERENCES top_destination (id)');
        $this->addSql('CREATE INDEX IDX_E01FBE6A451C64E9 ON images (top_destination_id)');
        $this->addSql('ALTER TABLE top_destination ADD image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE top_destination ADD CONSTRAINT FK_20E0B1483DA5256D FOREIGN KEY (image_id) REFERENCES images (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_20E0B1483DA5256D ON top_destination (image_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE images DROP FOREIGN KEY FK_E01FBE6A451C64E9');
        $this->addSql('DROP INDEX IDX_E01FBE6A451C64E9 ON images');
        $this->addSql('ALTER TABLE images DROP top_destination_id');
        $this->addSql('ALTER TABLE top_destination DROP FOREIGN KEY FK_20E0B1483DA5256D');
        $this->addSql('DROP INDEX UNIQ_20E0B1483DA5256D ON top_destination');
        $this->addSql('ALTER TABLE top_destination DROP image_id');
    }
}

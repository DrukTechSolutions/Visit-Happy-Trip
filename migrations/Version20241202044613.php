<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241202044613 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tour_category DROP FOREIGN KEY FK_9CB340F2F7BFE87C');
        $this->addSql('ALTER TABLE tour_category ADD CONSTRAINT FK_9CB340F2F7BFE87C FOREIGN KEY (sub_category_id) REFERENCES tour_category (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tour_category DROP FOREIGN KEY FK_9CB340F2F7BFE87C');
        $this->addSql('ALTER TABLE tour_category ADD CONSTRAINT FK_9CB340F2F7BFE87C FOREIGN KEY (sub_category_id) REFERENCES tour_category (id) ON UPDATE NO ACTION ON DELETE CASCADE');
    }
}

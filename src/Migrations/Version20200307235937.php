<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200307235937 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE slide_gallery ADD additional_gallery_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE slide_gallery ADD CONSTRAINT FK_9632F93311528860 FOREIGN KEY (additional_gallery_id) REFERENCES media__gallery (id)');
        $this->addSql('CREATE INDEX IDX_9632F93311528860 ON slide_gallery (additional_gallery_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE slide_gallery DROP FOREIGN KEY FK_9632F93311528860');
        $this->addSql('DROP INDEX IDX_9632F93311528860 ON slide_gallery');
        $this->addSql('ALTER TABLE slide_gallery DROP additional_gallery_id');
    }
}

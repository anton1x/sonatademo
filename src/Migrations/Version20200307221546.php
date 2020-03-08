<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200307221546 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE media_slide_gallery');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE media_slide_gallery (media_id INT NOT NULL, slide_gallery_id INT NOT NULL, INDEX IDX_7255C7924DC5951B (slide_gallery_id), INDEX IDX_7255C792EA9FDD75 (media_id), PRIMARY KEY(media_id, slide_gallery_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE media_slide_gallery ADD CONSTRAINT FK_7255C7924DC5951B FOREIGN KEY (slide_gallery_id) REFERENCES slide_gallery (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE media_slide_gallery ADD CONSTRAINT FK_7255C792EA9FDD75 FOREIGN KEY (media_id) REFERENCES media__media (id) ON DELETE CASCADE');
    }
}

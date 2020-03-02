<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200225184117 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE slide_gallery (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE slide_abstract ADD slide_gallery_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE slide_abstract ADD CONSTRAINT FK_C38FE844DC5951B FOREIGN KEY (slide_gallery_id) REFERENCES slide_gallery (id)');
        $this->addSql('CREATE INDEX IDX_C38FE844DC5951B ON slide_abstract (slide_gallery_id)');
        $this->addSql('ALTER TABLE base_product ADD CONSTRAINT FK_E74CBDC94B70279 FOREIGN KEY (pricing_type_id) REFERENCES pricing_type (id)');
        $this->addSql('CREATE INDEX IDX_E74CBDC94B70279 ON base_product (pricing_type_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE slide_abstract DROP FOREIGN KEY FK_C38FE844DC5951B');
        $this->addSql('DROP TABLE slide_gallery');
        $this->addSql('ALTER TABLE base_product DROP FOREIGN KEY FK_E74CBDC94B70279');
        $this->addSql('DROP INDEX IDX_E74CBDC94B70279 ON base_product');
        $this->addSql('DROP INDEX IDX_C38FE844DC5951B ON slide_abstract');
        $this->addSql('ALTER TABLE slide_abstract DROP slide_gallery_id');
    }
}

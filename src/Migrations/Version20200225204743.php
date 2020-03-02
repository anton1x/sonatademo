<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200225204743 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE slide (id INT AUTO_INCREMENT NOT NULL, slide_gallery_id INT DEFAULT NULL, image_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, body LONGTEXT NOT NULL, styles LONGTEXT NOT NULL, link LONGTEXT NOT NULL, INDEX IDX_72EFEE624DC5951B (slide_gallery_id), UNIQUE INDEX UNIQ_72EFEE623DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE slide ADD CONSTRAINT FK_72EFEE624DC5951B FOREIGN KEY (slide_gallery_id) REFERENCES slide_gallery (id)');
        $this->addSql('ALTER TABLE slide ADD CONSTRAINT FK_72EFEE623DA5256D FOREIGN KEY (image_id) REFERENCES media__media (id)');
        $this->addSql('DROP TABLE slide_abstract');
        $this->addSql('ALTER TABLE base_product ADD CONSTRAINT FK_E74CBDC94B70279 FOREIGN KEY (pricing_type_id) REFERENCES pricing_type (id)');
        $this->addSql('CREATE INDEX IDX_E74CBDC94B70279 ON base_product (pricing_type_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE slide_abstract (id INT AUTO_INCREMENT NOT NULL, image_id INT DEFAULT NULL, slide_gallery_id INT DEFAULT NULL, type VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, body LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, styles LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, link LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, title VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_C38FE844DC5951B (slide_gallery_id), INDEX IDX_C38FE843DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE slide_abstract ADD CONSTRAINT FK_C38FE843DA5256D FOREIGN KEY (image_id) REFERENCES media__media (id)');
        $this->addSql('ALTER TABLE slide_abstract ADD CONSTRAINT FK_C38FE844DC5951B FOREIGN KEY (slide_gallery_id) REFERENCES slide_gallery (id)');
        $this->addSql('DROP TABLE slide');
        $this->addSql('ALTER TABLE base_product DROP FOREIGN KEY FK_E74CBDC94B70279');
        $this->addSql('DROP INDEX IDX_E74CBDC94B70279 ON base_product');
    }
}

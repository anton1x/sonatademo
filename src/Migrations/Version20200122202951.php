<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200122202951 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE news_item DROP FOREIGN KEY FK_CAC6D395F4B2FBB3');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADF4B2FBB3');
        $this->addSql('DROP TABLE seo_info');
        $this->addSql('DROP INDEX UNIQ_D34A04ADF4B2FBB3 ON product');
        $this->addSql('ALTER TABLE product ADD seo_info_meta VARCHAR(1023) DEFAULT NULL, ADD seo_info_description VARCHAR(1023) DEFAULT NULL, DROP seo_info_id');
        $this->addSql('DROP INDEX UNIQ_CAC6D395F4B2FBB3 ON news_item');
        $this->addSql('ALTER TABLE news_item ADD seo_info_meta VARCHAR(1023) DEFAULT NULL, ADD seo_info_description VARCHAR(1023) DEFAULT NULL, DROP seo_info_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE seo_info (id INT AUTO_INCREMENT NOT NULL, meta VARCHAR(1023) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, description VARCHAR(1023) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE news_item ADD seo_info_id INT DEFAULT NULL, DROP seo_info_meta, DROP seo_info_description');
        $this->addSql('ALTER TABLE news_item ADD CONSTRAINT FK_CAC6D395F4B2FBB3 FOREIGN KEY (seo_info_id) REFERENCES seo_info (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CAC6D395F4B2FBB3 ON news_item (seo_info_id)');
        $this->addSql('ALTER TABLE product ADD seo_info_id INT DEFAULT NULL, DROP seo_info_meta, DROP seo_info_description');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADF4B2FBB3 FOREIGN KEY (seo_info_id) REFERENCES seo_info (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D34A04ADF4B2FBB3 ON product (seo_info_id)');
    }
}

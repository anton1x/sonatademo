<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200226114345 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE slide CHANGE body body LONGTEXT DEFAULT NULL, CHANGE styles styles LONGTEXT DEFAULT NULL, CHANGE link link LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE base_product ADD CONSTRAINT FK_E74CBDC94B70279 FOREIGN KEY (pricing_type_id) REFERENCES pricing_type (id)');
        $this->addSql('CREATE INDEX IDX_E74CBDC94B70279 ON base_product (pricing_type_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE base_product DROP FOREIGN KEY FK_E74CBDC94B70279');
        $this->addSql('DROP INDEX IDX_E74CBDC94B70279 ON base_product');
        $this->addSql('ALTER TABLE slide CHANGE body body LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE styles styles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE link link LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}

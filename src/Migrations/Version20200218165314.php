<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200218165314 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE internet_plan_address_object');
        $this->addSql('ALTER TABLE base_product CHANGE pricing_type_id pricing_type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE base_product ADD CONSTRAINT FK_E74CBDC94B70279 FOREIGN KEY (pricing_type_id) REFERENCES pricing_type (id)');
        $this->addSql('CREATE INDEX IDX_E74CBDC94B70279 ON base_product (pricing_type_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE internet_plan_address_object (internet_plan_id INT NOT NULL, address_object_id INT NOT NULL, INDEX IDX_D12EB27DB96C6A63 (address_object_id), INDEX IDX_D12EB27DEE7203BE (internet_plan_id), PRIMARY KEY(internet_plan_id, address_object_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE internet_plan_address_object ADD CONSTRAINT FK_D12EB27DB96C6A63 FOREIGN KEY (address_object_id) REFERENCES address_object (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE internet_plan_address_object ADD CONSTRAINT FK_D12EB27DEE7203BE FOREIGN KEY (internet_plan_id) REFERENCES base_product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE base_product DROP FOREIGN KEY FK_E74CBDC94B70279');
        $this->addSql('DROP INDEX IDX_E74CBDC94B70279 ON base_product');
        $this->addSql('ALTER TABLE base_product CHANGE pricing_type_id pricing_type_id INT NOT NULL');
    }
}

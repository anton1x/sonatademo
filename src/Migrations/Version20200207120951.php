<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200207120951 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE tvplan_address_object (tvplan_id INT NOT NULL, address_object_id INT NOT NULL, INDEX IDX_C6F3FA2E548CC385 (tvplan_id), INDEX IDX_C6F3FA2EB96C6A63 (address_object_id), PRIMARY KEY(tvplan_id, address_object_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tvplan_address_object ADD CONSTRAINT FK_C6F3FA2E548CC385 FOREIGN KEY (tvplan_id) REFERENCES base_product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tvplan_address_object ADD CONSTRAINT FK_C6F3FA2EB96C6A63 FOREIGN KEY (address_object_id) REFERENCES address_object (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE address_object_tvplan');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE address_object_tvplan (address_object_id INT NOT NULL, tvplan_id INT NOT NULL, INDEX IDX_6F14B742548CC385 (tvplan_id), INDEX IDX_6F14B742B96C6A63 (address_object_id), PRIMARY KEY(address_object_id, tvplan_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE address_object_tvplan ADD CONSTRAINT FK_6F14B742548CC385 FOREIGN KEY (tvplan_id) REFERENCES base_product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE address_object_tvplan ADD CONSTRAINT FK_6F14B742B96C6A63 FOREIGN KEY (address_object_id) REFERENCES address_object (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE tvplan_address_object');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200211131153 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE connection_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE connection_type_device (connection_type_id INT NOT NULL, device_id INT NOT NULL, INDEX IDX_828314ABE466AB0 (connection_type_id), INDEX IDX_828314A94A4C7D4 (device_id), PRIMARY KEY(connection_type_id, device_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE connection_type_device ADD CONSTRAINT FK_828314ABE466AB0 FOREIGN KEY (connection_type_id) REFERENCES connection_type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE connection_type_device ADD CONSTRAINT FK_828314A94A4C7D4 FOREIGN KEY (device_id) REFERENCES base_product (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE connection_type_device DROP FOREIGN KEY FK_828314ABE466AB0');
        $this->addSql('DROP TABLE connection_type');
        $this->addSql('DROP TABLE connection_type_device');
    }
}

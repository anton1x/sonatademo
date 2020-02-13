<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200211131758 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE address_object ADD connection_type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE address_object ADD CONSTRAINT FK_93F42E53BE466AB0 FOREIGN KEY (connection_type_id) REFERENCES connection_type (id)');
        $this->addSql('CREATE INDEX IDX_93F42E53BE466AB0 ON address_object (connection_type_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE address_object DROP FOREIGN KEY FK_93F42E53BE466AB0');
        $this->addSql('DROP INDEX IDX_93F42E53BE466AB0 ON address_object');
        $this->addSql('ALTER TABLE address_object DROP connection_type_id');
    }
}

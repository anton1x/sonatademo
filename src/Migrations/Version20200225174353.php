<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200225174353 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE slide_abstract ADD image_id INT DEFAULT NULL, ADD body LONGTEXT DEFAULT NULL, ADD styles LONGTEXT DEFAULT NULL, ADD link LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE slide_abstract ADD CONSTRAINT FK_C38FE843DA5256D FOREIGN KEY (image_id) REFERENCES media__media (id)');
        $this->addSql('CREATE INDEX IDX_C38FE843DA5256D ON slide_abstract (image_id)');

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE slide_abstract DROP FOREIGN KEY FK_C38FE843DA5256D');
        $this->addSql('DROP INDEX IDX_C38FE843DA5256D ON slide_abstract');
        $this->addSql('ALTER TABLE slide_abstract DROP image_id, DROP body, DROP styles, DROP link');
    }
}

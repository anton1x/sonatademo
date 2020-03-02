<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200227230727 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE news_item ADD category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE news_item ADD CONSTRAINT FK_CAC6D39512469DE2 FOREIGN KEY (category_id) REFERENCES classification__category (id)');
        $this->addSql('CREATE INDEX IDX_CAC6D39512469DE2 ON news_item (category_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE news_item DROP FOREIGN KEY FK_CAC6D39512469DE2');
        $this->addSql('DROP INDEX IDX_CAC6D39512469DE2 ON news_item');
        $this->addSql('ALTER TABLE news_item DROP category_id');
    }
}

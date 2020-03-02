<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200227084122 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE INDEX IDX_E74CBDC94B70279 ON base_product (pricing_type_id)');
        $this->addSql('ALTER TABLE news_item ADD image_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE news_item ADD CONSTRAINT FK_CAC6D3953DA5256D FOREIGN KEY (image_id) REFERENCES media__media (id)');
        $this->addSql('CREATE INDEX IDX_CAC6D3953DA5256D ON news_item (image_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX IDX_E74CBDC94B70279 ON base_product');
        $this->addSql('ALTER TABLE news_item DROP FOREIGN KEY FK_CAC6D3953DA5256D');
        $this->addSql('DROP INDEX IDX_CAC6D3953DA5256D ON news_item');
        $this->addSql('ALTER TABLE news_item DROP image_id');
    }
}

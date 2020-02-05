<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200203135619 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE internet_plan_address_object DROP FOREIGN KEY FK_D12EB27DEE7203BE');
        $this->addSql('CREATE TABLE base_product (id INT AUTO_INCREMENT NOT NULL, image_id INT DEFAULT NULL, category_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description TEXT NOT NULL, seo_info_meta VARCHAR(1023) DEFAULT NULL, seo_info_description VARCHAR(1023) DEFAULT NULL, price_connection_price INT NOT NULL, price_monthly_price INT NOT NULL, type VARCHAR(255) NOT NULL, speed INT DEFAULT NULL, channel_count INT DEFAULT NULL, INDEX IDX_E74CBDC93DA5256D (image_id), INDEX IDX_E74CBDC912469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE base_product ADD CONSTRAINT FK_E74CBDC93DA5256D FOREIGN KEY (image_id) REFERENCES media__media (id)');
        $this->addSql('ALTER TABLE base_product ADD CONSTRAINT FK_E74CBDC912469DE2 FOREIGN KEY (category_id) REFERENCES classification__category (id) ON DELETE SET NULL');
        $this->addSql('DROP TABLE internet_plan');
        $this->addSql('DROP TABLE tvplan');
        $this->addSql('ALTER TABLE internet_plan_address_object DROP FOREIGN KEY FK_D12EB27DEE7203BE');
        $this->addSql('ALTER TABLE internet_plan_address_object ADD CONSTRAINT FK_D12EB27DEE7203BE FOREIGN KEY (internet_plan_id) REFERENCES base_product (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE internet_plan_address_object DROP FOREIGN KEY FK_D12EB27DEE7203BE');
        $this->addSql('CREATE TABLE internet_plan (id INT AUTO_INCREMENT NOT NULL, image_id INT DEFAULT NULL, category_id INT DEFAULT NULL, title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, speed INT NOT NULL, seo_info_meta VARCHAR(1023) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, seo_info_description VARCHAR(1023) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, price_connection_price INT NOT NULL, price_monthly_price INT NOT NULL, INDEX IDX_2A106E533DA5256D (image_id), INDEX IDX_2A106E5312469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE tvplan (id INT AUTO_INCREMENT NOT NULL, image_id INT DEFAULT NULL, category_id INT DEFAULT NULL, title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, seo_info_meta VARCHAR(1023) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, seo_info_description VARCHAR(1023) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, price_connection_price INT NOT NULL, price_monthly_price INT NOT NULL, channel_count INT NOT NULL, INDEX IDX_ABFD7F7A12469DE2 (category_id), INDEX IDX_ABFD7F7A3DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE internet_plan ADD CONSTRAINT FK_2A106E5312469DE2 FOREIGN KEY (category_id) REFERENCES classification__category (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE internet_plan ADD CONSTRAINT FK_2A106E533DA5256D FOREIGN KEY (image_id) REFERENCES media__media (id)');
        $this->addSql('ALTER TABLE tvplan ADD CONSTRAINT FK_ABFD7F7A12469DE2 FOREIGN KEY (category_id) REFERENCES classification__category (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE tvplan ADD CONSTRAINT FK_ABFD7F7A3DA5256D FOREIGN KEY (image_id) REFERENCES media__media (id)');
        $this->addSql('DROP TABLE base_product');
        $this->addSql('ALTER TABLE internet_plan_address_object DROP FOREIGN KEY FK_D12EB27DEE7203BE');
        $this->addSql('ALTER TABLE internet_plan_address_object ADD CONSTRAINT FK_D12EB27DEE7203BE FOREIGN KEY (internet_plan_id) REFERENCES internet_plan (id) ON DELETE CASCADE');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Entity\MenuSchemaItem;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Doctrine\Migrations\Version\Version;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200307010709 extends AbstractMigration implements ContainerAwareInterface
{
    private $container;

    public function getDescription() : string
    {
        return '';
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }


    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE menu_schema_item ADD link_attributes LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE menu_schema_item DROP link_attributes');
    }

    public function postUp($schema):void
    {
        $em = $this->container->get('doctrine.orm.entity_manager');

        $rootMenu = new MenuSchemaItem();

        $rootMenu->setLabel('-----------');
        $em->persist($rootMenu);
        $em->flush();
    }
}

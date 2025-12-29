<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251224122739 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE attribute_value CHANGE attribute_id attribute_id INT NOT NULL');
        $this->addSql('ALTER TABLE product_attribute_value CHANGE attribute_value_id attribute_value_id INT NOT NULL, CHANGE product_id product_id INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE attribute_value CHANGE attribute_id attribute_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE product_attribute_value CHANGE product_id product_id INT DEFAULT NULL, CHANGE attribute_value_id attribute_value_id INT DEFAULT NULL');
    }
}

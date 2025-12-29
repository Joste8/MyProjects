<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251222172218 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE variant_attribute DROP FOREIGN KEY `FK_198634A83B69A9AF`');
        $this->addSql('ALTER TABLE variant_attribute DROP FOREIGN KEY `FK_198634A865A22152`');
        $this->addSql('DROP TABLE variant_attribute');
        $this->addSql('ALTER TABLE product_variant DROP price, DROP stock');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE variant_attribute (id INT AUTO_INCREMENT NOT NULL, variant_id INT NOT NULL, attribute_value_id INT NOT NULL, INDEX IDX_198634A865A22152 (attribute_value_id), INDEX IDX_198634A83B69A9AF (variant_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE variant_attribute ADD CONSTRAINT `FK_198634A83B69A9AF` FOREIGN KEY (variant_id) REFERENCES product_variant (id)');
        $this->addSql('ALTER TABLE variant_attribute ADD CONSTRAINT `FK_198634A865A22152` FOREIGN KEY (attribute_value_id) REFERENCES attribute_value (id)');
        $this->addSql('ALTER TABLE product_variant ADD price INT NOT NULL, ADD stock INT NOT NULL');
    }
}

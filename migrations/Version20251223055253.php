<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251223055253 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

  public function up(Schema $schema): void
{
   // $this->addSql('ALTER TABLE product_attribute_value DROP FOREIGN KEY `FK_PAV_PRODUCT`');

    //$this->addSql('ALTER TABLE product_attribute_value DROP value, CHANGE attribute_id attribute_value_id INT NOT NULL');

   // $this->addSql('ALTER TABLE product_attribute_value ADD CONSTRAINT FK_CCC4BE1F65A22152 FOREIGN KEY (attribute_value_id) REFERENCES attribute_value (id)');
    //$this->addSql('CREATE INDEX IDX_CCC4BE1F65A22152 ON product_attribute_value (attribute_value_id)');

   // $this->addSql('CREATE INDEX IDX_CCC4BE1F4584665A ON product_attribute_value (product_id)');

   // $this->addSql('ALTER TABLE product_attribute_value ADD CONSTRAINT `FK_PAV_PRODUCT` FOREIGN KEY (product_id) REFERENCES product (id)');
}


    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE variant_attribute (id INT AUTO_INCREMENT NOT NULL, variant_id INT NOT NULL, attribute_value_id INT NOT NULL, INDEX IDX_198634A83B69A9AF (variant_id), INDEX IDX_198634A865A22152 (attribute_value_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE variant_attribute ADD CONSTRAINT `FK_198634A83B69A9AF` FOREIGN KEY (variant_id) REFERENCES product_variant (id)');
        $this->addSql('ALTER TABLE variant_attribute ADD CONSTRAINT `FK_198634A865A22152` FOREIGN KEY (attribute_value_id) REFERENCES attribute_value (id)');
        $this->addSql('ALTER TABLE product_variant_attribute_value DROP FOREIGN KEY FK_A44FC90FA80EF684');
        $this->addSql('ALTER TABLE product_variant_attribute_value DROP FOREIGN KEY FK_A44FC90F65A22152');
        $this->addSql('DROP TABLE product_variant_attribute_value');
        $this->addSql('ALTER TABLE attribute_value CHANGE value value VARCHAR(100) NOT NULL, CHANGE attribute_id attribute_id INT NOT NULL');
        $this->addSql('ALTER TABLE product_attribute_value DROP FOREIGN KEY FK_CCC4BE1F65A22152');
        $this->addSql('DROP INDEX IDX_CCC4BE1F65A22152 ON product_attribute_value');
        $this->addSql('ALTER TABLE product_attribute_value DROP FOREIGN KEY FK_CCC4BE1F4584665A');
        $this->addSql('ALTER TABLE product_attribute_value ADD value VARCHAR(100) NOT NULL, CHANGE attribute_value_id attribute_id INT NOT NULL');
        $this->addSql('ALTER TABLE product_attribute_value ADD CONSTRAINT `FK_CCC4BE1FB6E62EFA` FOREIGN KEY (attribute_id) REFERENCES product_attribute (id)');
        $this->addSql('CREATE INDEX IDX_CCC4BE1FB6E62EFA ON product_attribute_value (attribute_id)');
        $this->addSql('DROP INDEX idx_ccc4be1f4584665a ON product_attribute_value');
        $this->addSql('CREATE INDEX FK_PAV_PRODUCT ON product_attribute_value (product_id)');
        $this->addSql('ALTER TABLE product_attribute_value ADD CONSTRAINT FK_CCC4BE1F4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_variant ADD price INT NOT NULL, ADD stock INT NOT NULL');
    }
}

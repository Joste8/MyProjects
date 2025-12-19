<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251218114459 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE variant_attribute (id INT AUTO_INCREMENT NOT NULL, value VARCHAR(100) NOT NULL, variant_id INT NOT NULL, attribute_id INT NOT NULL, INDEX IDX_198634A83B69A9AF (variant_id), INDEX IDX_198634A8B6E62EFA (attribute_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE variant_attribute ADD CONSTRAINT FK_198634A83B69A9AF FOREIGN KEY (variant_id) REFERENCES product_variant (id)');
        $this->addSql('ALTER TABLE variant_attribute ADD CONSTRAINT FK_198634A8B6E62EFA FOREIGN KEY (attribute_id) REFERENCES attribute (id)');
        $this->addSql('ALTER TABLE product_variant DROP size, DROP color');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE variant_attribute DROP FOREIGN KEY FK_198634A83B69A9AF');
        $this->addSql('ALTER TABLE variant_attribute DROP FOREIGN KEY FK_198634A8B6E62EFA');
        $this->addSql('DROP TABLE variant_attribute');
        $this->addSql('ALTER TABLE product_variant ADD size VARCHAR(50) NOT NULL, ADD color VARCHAR(50) NOT NULL');
    }
}

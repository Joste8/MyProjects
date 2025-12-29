<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251222161606 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product_variant_attribute_value (product_variant_id INT NOT NULL, attribute_value_id INT NOT NULL, INDEX IDX_A44FC90FA80EF684 (product_variant_id), INDEX IDX_A44FC90F65A22152 (attribute_value_id), PRIMARY KEY (product_variant_id, attribute_value_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE product_variant_attribute_value ADD CONSTRAINT FK_A44FC90FA80EF684 FOREIGN KEY (product_variant_id) REFERENCES product_variant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_variant_attribute_value ADD CONSTRAINT FK_A44FC90F65A22152 FOREIGN KEY (attribute_value_id) REFERENCES attribute_value (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_variant_attribute_value DROP FOREIGN KEY FK_A44FC90FA80EF684');
        $this->addSql('ALTER TABLE product_variant_attribute_value DROP FOREIGN KEY FK_A44FC90F65A22152');
        $this->addSql('DROP TABLE product_variant_attribute_value');
    }
}

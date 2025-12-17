<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251215064758 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product_variant (id INT AUTO_INCREMENT NOT NULL, size VARCHAR(255) NOT NULL, stock VARCHAR(255) NOT NULL, many_to_one VARCHAR(255) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE product_size DROP FOREIGN KEY `FK_7A2806CB4584665A`');
        $this->addSql('DROP TABLE product_size');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product_size (id INT AUTO_INCREMENT NOT NULL, size_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, stock INT NOT NULL, product_id INT DEFAULT NULL, INDEX IDX_7A2806CB4584665A (product_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE product_size ADD CONSTRAINT `FK_7A2806CB4584665A` FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('DROP TABLE product_variant');
    }
}

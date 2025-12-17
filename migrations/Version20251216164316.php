<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251216164316 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP description');
        $this->addSql('ALTER TABLE product_variant ADD description LONGTEXT DEFAULT NULL, DROP color, CHANGE size name VARCHAR(255) NOT NULL, CHANGE stock price INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product ADD description LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE product_variant ADD color VARCHAR(50) NOT NULL, DROP description, CHANGE name size VARCHAR(255) NOT NULL, CHANGE price stock INT NOT NULL');
    }
}

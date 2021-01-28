<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210128170039 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(
            'CREATE TABLE vat (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', label VARCHAR(50) NOT NULL, value NUMERIC(5, 2) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );
        $this->addSql('ALTER TABLE product ADD vat_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', DROP vat');
        $this->addSql(
            'ALTER TABLE product ADD CONSTRAINT FK_D34A04ADB5B63A6B FOREIGN KEY (vat_id) REFERENCES vat (id)'
        );
        $this->addSql('CREATE INDEX IDX_D34A04ADB5B63A6B ON product (vat_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADB5B63A6B');
        $this->addSql('DROP TABLE vat');
        $this->addSql('DROP INDEX IDX_D34A04ADB5B63A6B ON product');
        $this->addSql('ALTER TABLE product ADD vat NUMERIC(5, 2) NOT NULL, DROP vat_id');
    }

    public function isTransactional(): bool
    {
        return false;
    }
}

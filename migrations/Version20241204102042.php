<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241204102042 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Init database.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE companies (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', name VARCHAR(255) NOT NULL, tax_id VARCHAR(255) NOT NULL, country VARCHAR(2) NOT NULL, address VARCHAR(255) NOT NULL, postal_code VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE employees (id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', company_id BINARY(16) NOT NULL COMMENT \'(DC2Type:ulid)\', first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, phone VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_BA82C300979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE employees ADD CONSTRAINT FK_BA82C300979B1AD6 FOREIGN KEY (company_id) REFERENCES companies (id)');
    }
}

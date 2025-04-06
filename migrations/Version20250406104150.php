<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250406104150 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__theme AS SELECT id, name, description, created_at, updated_at, content, category FROM theme');
        $this->addSql('DROP TABLE theme');
        $this->addSql('CREATE TABLE theme (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, test_settings_id INTEGER DEFAULT NULL, name VARCHAR(50) NOT NULL, description VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , content CLOB DEFAULT NULL, category INTEGER DEFAULT 1 NOT NULL, CONSTRAINT FK_9775E7086EA2336 FOREIGN KEY (test_settings_id) REFERENCES test_settings (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO theme (id, name, description, created_at, updated_at, content, category) SELECT id, name, description, created_at, updated_at, content, category FROM __temp__theme');
        $this->addSql('DROP TABLE __temp__theme');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9775E7086EA2336 ON theme (test_settings_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__theme AS SELECT id, name, description, category, content, created_at, updated_at FROM theme');
        $this->addSql('DROP TABLE theme');
        $this->addSql('CREATE TABLE theme (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(50) NOT NULL, description VARCHAR(255) DEFAULT NULL, category INTEGER DEFAULT 1 NOT NULL, content CLOB DEFAULT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('INSERT INTO theme (id, name, description, category, content, created_at, updated_at) SELECT id, name, description, category, content, created_at, updated_at FROM __temp__theme');
        $this->addSql('DROP TABLE __temp__theme');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240911140504 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_statistics (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, theme_id INTEGER DEFAULT NULL, correct_answers INTEGER DEFAULT NULL, incorrect_answers INTEGER DEFAULT NULL, total_attempts INTEGER DEFAULT NULL, user_level VARCHAR(255) DEFAULT NULL, last_attempt_date DATETIME DEFAULT NULL, CONSTRAINT FK_45B44DCEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_45B44DCE59027487 FOREIGN KEY (theme_id) REFERENCES theme (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_45B44DCEA76ED395 ON user_statistics (user_id)');
        $this->addSql('CREATE INDEX IDX_45B44DCE59027487 ON user_statistics (theme_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__theme AS SELECT id, name, description, created_at, updated_at FROM theme');
        $this->addSql('DROP TABLE theme');
        $this->addSql('CREATE TABLE theme (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(50) NOT NULL, description VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('INSERT INTO theme (id, name, description, created_at, updated_at) SELECT id, name, description, created_at, updated_at FROM __temp__theme');
        $this->addSql('DROP TABLE __temp__theme');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user_statistics');
        $this->addSql('CREATE TEMPORARY TABLE __temp__theme AS SELECT id, name, description, created_at, updated_at FROM theme');
        $this->addSql('DROP TABLE theme');
        $this->addSql('CREATE TABLE theme (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(50) NOT NULL, description VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL)');
        $this->addSql('INSERT INTO theme (id, name, description, created_at, updated_at) SELECT id, name, description, created_at, updated_at FROM __temp__theme');
        $this->addSql('DROP TABLE __temp__theme');
    }
}

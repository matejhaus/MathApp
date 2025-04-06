<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250406102920 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE test_settings (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, time_limit_in_minutes INTEGER DEFAULT NULL, number_of_questions INTEGER DEFAULT NULL, random_order BOOLEAN NOT NULL, passing_score_percentage INTEGER DEFAULT NULL, show_correct_answers_after BOOLEAN NOT NULL, is_practice_mode BOOLEAN NOT NULL, access_code VARCHAR(255) DEFAULT NULL)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE test_settings');
    }
}

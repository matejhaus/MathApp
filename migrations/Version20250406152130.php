<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250406152130 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__test_settings AS SELECT id, time_limit_in_minutes, number_of_questions, random_order, show_correct_answers_after, is_practice_mode, access_code FROM test_settings');
        $this->addSql('DROP TABLE test_settings');
        $this->addSql('CREATE TABLE test_settings (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, time_limit_in_minutes INTEGER DEFAULT NULL, number_of_questions INTEGER DEFAULT NULL, random_order BOOLEAN NOT NULL, show_correct_answers_after BOOLEAN NOT NULL, is_practice_mode BOOLEAN NOT NULL, access_code VARCHAR(255) DEFAULT NULL, grade1_percentage DOUBLE PRECISION DEFAULT NULL, grade2_percentage DOUBLE PRECISION DEFAULT NULL, grade3_percentage DOUBLE PRECISION DEFAULT NULL, grade4_percentage DOUBLE PRECISION DEFAULT NULL, grade5_percentage DOUBLE PRECISION DEFAULT NULL)');
        $this->addSql('INSERT INTO test_settings (id, time_limit_in_minutes, number_of_questions, random_order, show_correct_answers_after, is_practice_mode, access_code) SELECT id, time_limit_in_minutes, number_of_questions, random_order, show_correct_answers_after, is_practice_mode, access_code FROM __temp__test_settings');
        $this->addSql('DROP TABLE __temp__test_settings');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__test_settings AS SELECT id, time_limit_in_minutes, number_of_questions, random_order, show_correct_answers_after, is_practice_mode, access_code FROM test_settings');
        $this->addSql('DROP TABLE test_settings');
        $this->addSql('CREATE TABLE test_settings (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, time_limit_in_minutes INTEGER DEFAULT NULL, number_of_questions INTEGER DEFAULT NULL, random_order BOOLEAN NOT NULL, show_correct_answers_after BOOLEAN NOT NULL, is_practice_mode BOOLEAN NOT NULL, access_code VARCHAR(255) DEFAULT NULL, passing_score_percentage INTEGER DEFAULT NULL)');
        $this->addSql('INSERT INTO test_settings (id, time_limit_in_minutes, number_of_questions, random_order, show_correct_answers_after, is_practice_mode, access_code) SELECT id, time_limit_in_minutes, number_of_questions, random_order, show_correct_answers_after, is_practice_mode, access_code FROM __temp__test_settings');
        $this->addSql('DROP TABLE __temp__test_settings');
    }
}

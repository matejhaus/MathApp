<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241103153230 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE example (id INT AUTO_INCREMENT NOT NULL, theme_id INT NOT NULL, question VARCHAR(255) NOT NULL, result VARCHAR(255) NOT NULL, INDEX IDX_6EEC9B9F59027487 (theme_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quotes (id INT AUTO_INCREMENT NOT NULL, position INT NOT NULL, quote VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE theme (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, description VARCHAR(255) DEFAULT NULL, content LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_attempts (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, theme_id INT NOT NULL, correct_answers INT NOT NULL, incorrect_answers INT NOT NULL, INDEX IDX_5F4A0364A76ED395 (user_id), INDEX IDX_5F4A036459027487 (theme_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_statistics (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, theme_id INT DEFAULT NULL, correct_answers INT DEFAULT NULL, incorrect_answers INT DEFAULT NULL, total_attempts INT DEFAULT NULL, user_level VARCHAR(255) DEFAULT NULL, last_attempt_date DATETIME DEFAULT NULL, INDEX IDX_45B44DCEA76ED395 (user_id), INDEX IDX_45B44DCE59027487 (theme_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE example ADD CONSTRAINT FK_6EEC9B9F59027487 FOREIGN KEY (theme_id) REFERENCES theme (id)');
        $this->addSql('ALTER TABLE user_attempts ADD CONSTRAINT FK_5F4A0364A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_attempts ADD CONSTRAINT FK_5F4A036459027487 FOREIGN KEY (theme_id) REFERENCES theme (id)');
        $this->addSql('ALTER TABLE user_statistics ADD CONSTRAINT FK_45B44DCEA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_statistics ADD CONSTRAINT FK_45B44DCE59027487 FOREIGN KEY (theme_id) REFERENCES theme (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE example DROP FOREIGN KEY FK_6EEC9B9F59027487');
        $this->addSql('ALTER TABLE user_attempts DROP FOREIGN KEY FK_5F4A0364A76ED395');
        $this->addSql('ALTER TABLE user_attempts DROP FOREIGN KEY FK_5F4A036459027487');
        $this->addSql('ALTER TABLE user_statistics DROP FOREIGN KEY FK_45B44DCEA76ED395');
        $this->addSql('ALTER TABLE user_statistics DROP FOREIGN KEY FK_45B44DCE59027487');
        $this->addSql('DROP TABLE example');
        $this->addSql('DROP TABLE quotes');
        $this->addSql('DROP TABLE theme');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_attempts');
        $this->addSql('DROP TABLE user_statistics');
        $this->addSql('DROP TABLE messenger_messages');
    }
}

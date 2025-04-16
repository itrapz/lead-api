<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250416022227 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE api_requests (id BIGINT AUTO_INCREMENT NOT NULL, request_method VARCHAR(10) NOT NULL, request_uri LONGTEXT NOT NULL, request_headers JSON DEFAULT NULL, request_body LONGTEXT DEFAULT NULL, ip_address VARCHAR(45) DEFAULT NULL, user_agent LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE api_responses (id BIGINT AUTO_INCREMENT NOT NULL, response_status SMALLINT NOT NULL, response_headers JSON DEFAULT NULL, response_body LONGTEXT DEFAULT NULL, execution_time_ms INT DEFAULT NULL, created_at DATETIME NOT NULL, api_request_id BIGINT NOT NULL, INDEX IDX_4C8B3AEF85D4C4B4 (api_request_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE leads (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, email VARCHAR(180) NOT NULL, dynamic_data JSON DEFAULT NULL, phone VARCHAR(20) NOT NULL, date_of_birth DATETIME NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(32) NOT NULL, last_name VARCHAR(32) NOT NULL, username VARCHAR(32) NOT NULL, email VARCHAR(180) NOT NULL, phone VARCHAR(20) NOT NULL, date_of_birth DATETIME NOT NULL, created_at DATETIME NOT NULL, api_token VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D6497BA2F5EB (api_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE api_responses ADD CONSTRAINT FK_4C8B3AEF85D4C4B4 FOREIGN KEY (api_request_id) REFERENCES api_requests (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE api_responses DROP FOREIGN KEY FK_4C8B3AEF85D4C4B4
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE api_requests
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE api_responses
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE leads
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user
        SQL);
    }
}

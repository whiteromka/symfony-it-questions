<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251016115951 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE question_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE question_category_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE question_history_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE question (id INT NOT NULL, category_id INT DEFAULT NULL, author_id INT DEFAULT NULL, text TEXT DEFAULT NULL, difficulty INT DEFAULT 5 NOT NULL, status INT DEFAULT 1 NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_B6F7494E12469DE2 ON question (category_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_B6F7494EF675F31B ON question (author_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN question.created_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE question_category (id INT NOT NULL, name VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, color VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN question_category.created_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE question_history (id INT NOT NULL, question_id INT DEFAULT NULL, last_time_showed TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_2966A5561E27F6BF ON question_history (question_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE "user" (id INT NOT NULL, name VARCHAR(255) NOT NULL, last_name VARCHAR(255) DEFAULT NULL, email VARCHAR(255) NOT NULL, phone VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, roles JSON NOT NULL, status INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN "user".created_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGSERIAL NOT NULL, body TEXT NOT NULL, headers TEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN messenger_messages.created_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN messenger_messages.available_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN messenger_messages.delivered_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE OR REPLACE FUNCTION notify_messenger_messages() RETURNS TRIGGER AS $$
                BEGIN
                    PERFORM pg_notify('messenger_messages', NEW.queue_name::text);
                    RETURN NEW;
                END;
            $$ LANGUAGE plpgsql;
        SQL);
        $this->addSql(<<<'SQL'
            DROP TRIGGER IF EXISTS notify_trigger ON messenger_messages;
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TRIGGER notify_trigger AFTER INSERT OR UPDATE ON messenger_messages FOR EACH ROW EXECUTE PROCEDURE notify_messenger_messages();
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE question ADD CONSTRAINT FK_B6F7494E12469DE2 FOREIGN KEY (category_id) REFERENCES question_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE question ADD CONSTRAINT FK_B6F7494EF675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE question_history ADD CONSTRAINT FK_2966A5561E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            DROP SEQUENCE question_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE question_category_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE question_history_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP SEQUENCE "user_id_seq" CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE question DROP CONSTRAINT FK_B6F7494E12469DE2
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE question DROP CONSTRAINT FK_B6F7494EF675F31B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE question_history DROP CONSTRAINT FK_2966A5561E27F6BF
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE question
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE question_category
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE question_history
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE "user"
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251101193207 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Добавил промежуточную тбл tag_to_blog с индексами и внешними ключами ';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE btag_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE tag_to_blog (blog_id INT NOT NULL, tag_id INT NOT NULL, PRIMARY KEY(blog_id, tag_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_15CA6D26DAE07E97 ON tag_to_blog (blog_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_15CA6D26BAD26311 ON tag_to_blog (tag_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE btag (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tag_to_blog ADD CONSTRAINT FK_15CA6D26DAE07E97 FOREIGN KEY (blog_id) REFERENCES blog (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tag_to_blog ADD CONSTRAINT FK_15CA6D26BAD26311 FOREIGN KEY (tag_id) REFERENCES btag (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            DROP SEQUENCE btag_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tag_to_blog DROP CONSTRAINT FK_15CA6D26DAE07E97
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tag_to_blog DROP CONSTRAINT FK_15CA6D26BAD26311
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE tag_to_blog
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE btag
        SQL);
    }
}

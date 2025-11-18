<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251118145912 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            CREATE TABLE user_to_skill (user_id INT NOT NULL, skill_id INT NOT NULL, PRIMARY KEY(user_id, skill_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_B58C3147A76ED395 ON user_to_skill (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_B58C31475585C142 ON user_to_skill (skill_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_to_skill ADD CONSTRAINT FK_B58C3147A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_to_skill ADD CONSTRAINT FK_B58C31475585C142 FOREIGN KEY (skill_id) REFERENCES skill (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            ALTER TABLE user_to_skill DROP CONSTRAINT FK_B58C3147A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_to_skill DROP CONSTRAINT FK_B58C31475585C142
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user_to_skill
        SQL);
    }
}

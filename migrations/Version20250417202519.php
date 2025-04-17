<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250417202519 extends AbstractMigration
{

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            ALTER TABLE question
                ADD CONSTRAINT fk_question__category_id
                FOREIGN KEY (category_id)
                REFERENCES question_category(id)
                ON DELETE CASCADE
                ON UPDATE CASCADE;
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            ALTER TABLE question
                DROP CONSTRAINT fk_question__category_id;
        SQL);

    }
}

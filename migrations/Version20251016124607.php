<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251016124607 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Установка дефолтных категорий вопросов';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            insert into "question_category" (id, name, created_at, color)
            values 
                (nextval('question_category_id_seq'), 'Общее', NOW(), 'grey'),
                (nextval('question_category_id_seq'), 'PHP', NOW(), 'grey')
        
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            DELETE FROM "question_category" WHERE name IN ('Общее', 'PHP')
        SQL);

        // Восстанавливаем последовательность после удаления
        $this->addSql('SELECT setval(\'question_category_id_seq\', (SELECT COALESCE(MAX(id), 1) FROM "question_category"), true)');
    }
}

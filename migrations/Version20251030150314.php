<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251030150314 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Добавим category_id в тбл blog. Внешний ключ и индекс на поле';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            ALTER TABLE blog ADD category_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE blog ADD CONSTRAINT FK_C015514312469DE2 FOREIGN KEY (category_id) REFERENCES blog_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_C015514312469DE2 ON blog (category_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            ALTER TABLE blog DROP CONSTRAINT FK_C015514312469DE2
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_C015514312469DE2
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE blog DROP category_id
        SQL);
    }
}

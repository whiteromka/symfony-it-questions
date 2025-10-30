<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251030143328 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Таблица blog_category';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            CREATE SEQUENCE blog_category_id_seq INCREMENT BY 1 MINVALUE 1 START 1
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE blog_category (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            DROP SEQUENCE blog_category_id_seq CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE blog_category
        SQL);
    }
}

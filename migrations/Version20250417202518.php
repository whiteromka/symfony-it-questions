<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250417202518 extends AbstractMigration
{

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            ALTER TABLE question DROP COLUMN category;
        SQL);

        $this->addSql(<<<'SQL'
            ALTER TABLE question ADD COLUMN category_id INT NOT NULL DEFAULT 1;
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            ALTER TABLE question DROP COLUMN category_id;
        SQL);

        $this->addSql(<<<'SQL'
            ALTER TABLE question ADD COLUMN category varchar DEFAULT NULL;
        SQL);
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251016150705 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    /**
     * php bin/console doctrine:migrations:migrate
     */
    public function up(Schema $schema): void
    {
        $sql = 'TRUNCATE TABLE question CASCADE;';
        $this->addSql($sql);

        $this->addSql(<<<'SQL'
            ALTER TABLE question ALTER author_id DROP NOT NULL
        SQL);
    }

    /**
     * php bin/console doctrine:migrations:migrate prev
     */
    public function down(Schema $schema): void
    {
        return;
    }
}

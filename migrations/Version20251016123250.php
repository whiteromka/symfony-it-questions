<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251016123250 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Первый пользователь';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            insert into "user" (id ,name, last_name, email, phone, password, roles, status)
            values (
                nextval('user_id_seq'),
                'Rom',
                'Belov',
                'rom@rom.ru',
                '89998887766',
                'abc',
                '["admin", "user"]'::jsonb,
                1
            );
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DELETE FROM "user" WHERE email = \'rom@rom.ru\'');

        // Восстанавливаем последовательность после удаления
        $this->addSql('SELECT setval(\'user_id_seq\', (SELECT COALESCE(MAX(id), 1) FROM "user"), true)');
    }
}

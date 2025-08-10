<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250809121843 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__choices AS SELECT id, room, description, item FROM choices
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE choices
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE choices (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, room INTEGER NOT NULL, choice VARCHAR(255) NOT NULL, require VARCHAR(50) DEFAULT NULL, gain VARCHAR(50) NOT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO choices (id, room, choice, require) SELECT id, room, description, item FROM __temp__choices
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__choices
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__choices AS SELECT id, room, choice, require FROM choices
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE choices
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE choices (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, room INTEGER NOT NULL, description VARCHAR(255) NOT NULL, item VARCHAR(50) DEFAULT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO choices (id, room, description, item) SELECT id, room, choice, require FROM __temp__choices
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__choices
        SQL);
    }
}

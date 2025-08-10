<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250803110421 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__library AS SELECT id, title, author, cover, isbn, description FROM library
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE library
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE library (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, author VARCHAR(255) NOT NULL, cover VARCHAR(255) NOT NULL, isbn VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO library (id, title, author, cover, isbn, description) SELECT id, title, author, cover, isbn, description FROM __temp__library
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__library
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__path AS SELECT id, from_room, direction, to_room, required_item FROM path
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE path
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE path (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, from_room INTEGER NOT NULL, direction VARCHAR(20) NOT NULL, to_room INTEGER NOT NULL, required_item INTEGER DEFAULT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO path (id, from_room, direction, to_room, required_item) SELECT id, from_room, direction, to_room, required_item FROM __temp__path
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__path
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__library AS SELECT id, title, author, cover, isbn, description FROM library
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE library
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE library (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, author VARCHAR(255) NOT NULL, cover VARCHAR(255) NOT NULL, isbn VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO library (id, title, author, cover, isbn, description) SELECT id, title, author, cover, isbn, description FROM __temp__library
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__library
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TEMPORARY TABLE __temp__path AS SELECT id, from_room, direction, to_room, required_item FROM path
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE path
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE path (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, from_room INTEGER NOT NULL, direction VARCHAR(20) NOT NULL, to_room INTEGER NOT NULL, required_item VARCHAR(20) DEFAULT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            INSERT INTO path (id, from_room, direction, to_room, required_item) SELECT id, from_room, direction, to_room, required_item FROM __temp__path
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE __temp__path
        SQL);
    }
}

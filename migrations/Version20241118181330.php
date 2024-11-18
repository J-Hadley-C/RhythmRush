<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241118181330 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE artist ADD created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE beatmaker ADD created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE producteur ADD created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE user DROP created_at');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE artist DROP created_at');
        $this->addSql('ALTER TABLE producteur DROP created_at');
        $this->addSql('ALTER TABLE beatmaker DROP created_at');
        $this->addSql('ALTER TABLE user ADD created_at DATETIME NOT NULL');
    }
}

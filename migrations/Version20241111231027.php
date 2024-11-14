<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241111231027 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE artist_style (artist_id INT NOT NULL, style_id INT NOT NULL, INDEX IDX_53B18839B7970CF8 (artist_id), INDEX IDX_53B18839BACD6074 (style_id), PRIMARY KEY(artist_id, style_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE beatmaker (id INT AUTO_INCREMENT NOT NULL, bio LONGTEXT NOT NULL, city VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE music_style (music_id INT NOT NULL, style_id INT NOT NULL, INDEX IDX_5434C3A2399BBB13 (music_id), INDEX IDX_5434C3A2BACD6074 (style_id), PRIMARY KEY(music_id, style_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE producteur_style (producteur_id INT NOT NULL, style_id INT NOT NULL, INDEX IDX_828D5411AB9BB300 (producteur_id), INDEX IDX_828D5411BACD6074 (style_id), PRIMARY KEY(producteur_id, style_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE artist_style ADD CONSTRAINT FK_53B18839B7970CF8 FOREIGN KEY (artist_id) REFERENCES artist (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE artist_style ADD CONSTRAINT FK_53B18839BACD6074 FOREIGN KEY (style_id) REFERENCES style (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE music_style ADD CONSTRAINT FK_5434C3A2399BBB13 FOREIGN KEY (music_id) REFERENCES music (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE music_style ADD CONSTRAINT FK_5434C3A2BACD6074 FOREIGN KEY (style_id) REFERENCES style (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE producteur_style ADD CONSTRAINT FK_828D5411AB9BB300 FOREIGN KEY (producteur_id) REFERENCES producteur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE producteur_style ADD CONSTRAINT FK_828D5411BACD6074 FOREIGN KEY (style_id) REFERENCES style (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE artist ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE artist ADD CONSTRAINT FK_1599687A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1599687A76ED395 ON artist (user_id)');
        $this->addSql('ALTER TABLE follow ADD producer_id INT NOT NULL, ADD artist_id INT NOT NULL');
        $this->addSql('ALTER TABLE follow ADD CONSTRAINT FK_6834447089B658FE FOREIGN KEY (producer_id) REFERENCES producteur (id)');
        $this->addSql('ALTER TABLE follow ADD CONSTRAINT FK_68344470B7970CF8 FOREIGN KEY (artist_id) REFERENCES artist (id)');
        $this->addSql('CREATE INDEX IDX_6834447089B658FE ON follow (producer_id)');
        $this->addSql('CREATE INDEX IDX_68344470B7970CF8 ON follow (artist_id)');
        $this->addSql('ALTER TABLE music ADD artist_id INT NOT NULL, CHANGE cover cover VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE music ADD CONSTRAINT FK_CD52224AB7970CF8 FOREIGN KEY (artist_id) REFERENCES artist (id)');
        $this->addSql('CREATE INDEX IDX_CD52224AB7970CF8 ON music (artist_id)');
        $this->addSql('ALTER TABLE producteur ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE producteur ADD CONSTRAINT FK_7EDBEE10A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7EDBEE10A76ED395 ON producteur (user_id)');
        $this->addSql('ALTER TABLE style CHANGE image image VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE artist_style DROP FOREIGN KEY FK_53B18839B7970CF8');
        $this->addSql('ALTER TABLE artist_style DROP FOREIGN KEY FK_53B18839BACD6074');
        $this->addSql('ALTER TABLE music_style DROP FOREIGN KEY FK_5434C3A2399BBB13');
        $this->addSql('ALTER TABLE music_style DROP FOREIGN KEY FK_5434C3A2BACD6074');
        $this->addSql('ALTER TABLE producteur_style DROP FOREIGN KEY FK_828D5411AB9BB300');
        $this->addSql('ALTER TABLE producteur_style DROP FOREIGN KEY FK_828D5411BACD6074');
        $this->addSql('DROP TABLE artist_style');
        $this->addSql('DROP TABLE beatmaker');
        $this->addSql('DROP TABLE music_style');
        $this->addSql('DROP TABLE producteur_style');
        $this->addSql('ALTER TABLE artist DROP FOREIGN KEY FK_1599687A76ED395');
        $this->addSql('DROP INDEX UNIQ_1599687A76ED395 ON artist');
        $this->addSql('ALTER TABLE artist DROP user_id');
        $this->addSql('ALTER TABLE music DROP FOREIGN KEY FK_CD52224AB7970CF8');
        $this->addSql('DROP INDEX IDX_CD52224AB7970CF8 ON music');
        $this->addSql('ALTER TABLE music DROP artist_id, CHANGE cover cover VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE follow DROP FOREIGN KEY FK_6834447089B658FE');
        $this->addSql('ALTER TABLE follow DROP FOREIGN KEY FK_68344470B7970CF8');
        $this->addSql('DROP INDEX IDX_6834447089B658FE ON follow');
        $this->addSql('DROP INDEX IDX_68344470B7970CF8 ON follow');
        $this->addSql('ALTER TABLE follow DROP producer_id, DROP artist_id');
        $this->addSql('ALTER TABLE style CHANGE image image VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE producteur DROP FOREIGN KEY FK_7EDBEE10A76ED395');
        $this->addSql('DROP INDEX UNIQ_7EDBEE10A76ED395 ON producteur');
        $this->addSql('ALTER TABLE producteur DROP user_id');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241111231406 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE beatmaker_style (beatmaker_id INT NOT NULL, style_id INT NOT NULL, INDEX IDX_51D49E01AD72BF (beatmaker_id), INDEX IDX_51D49E01BACD6074 (style_id), PRIMARY KEY(beatmaker_id, style_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE beatmaker_style ADD CONSTRAINT FK_51D49E01AD72BF FOREIGN KEY (beatmaker_id) REFERENCES beatmaker (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE beatmaker_style ADD CONSTRAINT FK_51D49E01BACD6074 FOREIGN KEY (style_id) REFERENCES style (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE beatmaker ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE beatmaker ADD CONSTRAINT FK_B1DE937CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B1DE937CA76ED395 ON beatmaker (user_id)');
        $this->addSql('ALTER TABLE music ADD beatmaker_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE music ADD CONSTRAINT FK_CD52224AAD72BF FOREIGN KEY (beatmaker_id) REFERENCES beatmaker (id)');
        $this->addSql('CREATE INDEX IDX_CD52224AAD72BF ON music (beatmaker_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE beatmaker_style DROP FOREIGN KEY FK_51D49E01AD72BF');
        $this->addSql('ALTER TABLE beatmaker_style DROP FOREIGN KEY FK_51D49E01BACD6074');
        $this->addSql('DROP TABLE beatmaker_style');
        $this->addSql('ALTER TABLE beatmaker DROP FOREIGN KEY FK_B1DE937CA76ED395');
        $this->addSql('DROP INDEX UNIQ_B1DE937CA76ED395 ON beatmaker');
        $this->addSql('ALTER TABLE beatmaker DROP user_id');
        $this->addSql('ALTER TABLE music DROP FOREIGN KEY FK_CD52224AAD72BF');
        $this->addSql('DROP INDEX IDX_CD52224AAD72BF ON music');
        $this->addSql('ALTER TABLE music DROP beatmaker_id');
    }
}

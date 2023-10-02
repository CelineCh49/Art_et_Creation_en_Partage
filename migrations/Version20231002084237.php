<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231002084237 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Entities relations';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE artist_category (artist_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_BE46F390B7970CF8 (artist_id), INDEX IDX_BE46F39012469DE2 (category_id), PRIMARY KEY(artist_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE artist_event (artist_id INT NOT NULL, event_id INT NOT NULL, INDEX IDX_5BA23AF4B7970CF8 (artist_id), INDEX IDX_5BA23AF471F7E88B (event_id), PRIMARY KEY(artist_id, event_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE artist_category ADD CONSTRAINT FK_BE46F390B7970CF8 FOREIGN KEY (artist_id) REFERENCES artist (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE artist_category ADD CONSTRAINT FK_BE46F39012469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE artist_event ADD CONSTRAINT FK_5BA23AF4B7970CF8 FOREIGN KEY (artist_id) REFERENCES artist (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE artist_event ADD CONSTRAINT FK_5BA23AF471F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE artist ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE artist ADD CONSTRAINT FK_1599687A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1599687A76ED395 ON artist (user_id)');
        $this->addSql('ALTER TABLE image ADD artist_id INT DEFAULT NULL, ADD event_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FB7970CF8 FOREIGN KEY (artist_id) REFERENCES artist (id)');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F71F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('CREATE INDEX IDX_C53D045FB7970CF8 ON image (artist_id)');
        $this->addSql('CREATE INDEX IDX_C53D045F71F7E88B ON image (event_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE artist_category DROP FOREIGN KEY FK_BE46F390B7970CF8');
        $this->addSql('ALTER TABLE artist_category DROP FOREIGN KEY FK_BE46F39012469DE2');
        $this->addSql('ALTER TABLE artist_event DROP FOREIGN KEY FK_5BA23AF4B7970CF8');
        $this->addSql('ALTER TABLE artist_event DROP FOREIGN KEY FK_5BA23AF471F7E88B');
        $this->addSql('DROP TABLE artist_category');
        $this->addSql('DROP TABLE artist_event');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045FB7970CF8');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F71F7E88B');
        $this->addSql('DROP INDEX IDX_C53D045FB7970CF8 ON image');
        $this->addSql('DROP INDEX IDX_C53D045F71F7E88B ON image');
        $this->addSql('ALTER TABLE image DROP artist_id, DROP event_id');
        $this->addSql('ALTER TABLE artist DROP FOREIGN KEY FK_1599687A76ED395');
        $this->addSql('DROP INDEX UNIQ_1599687A76ED395 ON artist');
        $this->addSql('ALTER TABLE artist DROP user_id');
    }
}

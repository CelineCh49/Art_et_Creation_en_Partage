<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231005104512 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add eventImage and Event relation';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event_image ADD event_id INT NOT NULL');
        $this->addSql('ALTER TABLE event_image ADD CONSTRAINT FK_8426B57371F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('CREATE INDEX IDX_8426B57371F7E88B ON event_image (event_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event_image DROP FOREIGN KEY FK_8426B57371F7E88B');
        $this->addSql('DROP INDEX IDX_8426B57371F7E88B ON event_image');
        $this->addSql('ALTER TABLE event_image DROP event_id');
    }
}

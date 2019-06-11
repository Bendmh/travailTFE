<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190605082959 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE brainstorming ADD activity_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE brainstorming ADD CONSTRAINT FK_67090B4081C06096 FOREIGN KEY (activity_id) REFERENCES activity (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_67090B4081C06096 ON brainstorming (activity_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE brainstorming DROP FOREIGN KEY FK_67090B4081C06096');
        $this->addSql('DROP INDEX UNIQ_67090B4081C06096 ON brainstorming');
        $this->addSql('ALTER TABLE brainstorming DROP activity_id');
    }
}

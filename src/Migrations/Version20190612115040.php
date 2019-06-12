<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190612115040 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE question_audio ADD activity_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE question_audio ADD CONSTRAINT FK_2896279181C06096 FOREIGN KEY (activity_id) REFERENCES activity (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2896279181C06096 ON question_audio (activity_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE question_audio DROP FOREIGN KEY FK_2896279181C06096');
        $this->addSql('DROP INDEX UNIQ_2896279181C06096 ON question_audio');
        $this->addSql('ALTER TABLE question_audio DROP activity_id');
    }
}

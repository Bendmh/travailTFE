<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190216103849 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6491156F50D');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6499E225B24');
        $this->addSql('DROP INDEX IDX_8D93D6491156F50D ON user');
        $this->addSql('DROP INDEX IDX_8D93D6499E225B24 ON user');
        $this->addSql('ALTER TABLE user DROP classes_id, DROP appartenance_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user ADD classes_id INT NOT NULL, ADD appartenance_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6491156F50D FOREIGN KEY (appartenance_id) REFERENCES classes (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6499E225B24 FOREIGN KEY (classes_id) REFERENCES classes (id)');
        $this->addSql('CREATE INDEX IDX_8D93D6491156F50D ON user (appartenance_id)');
        $this->addSql('CREATE INDEX IDX_8D93D6499E225B24 ON user (classes_id)');
    }
}

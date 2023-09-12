<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230912075232 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE guide ADD champion_id INT NOT NULL');
        $this->addSql('ALTER TABLE guide ADD CONSTRAINT FK_CA9EC735FA7FD7EB FOREIGN KEY (champion_id) REFERENCES data_champion (id)');
        $this->addSql('CREATE INDEX IDX_CA9EC735FA7FD7EB ON guide (champion_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE guide DROP FOREIGN KEY FK_CA9EC735FA7FD7EB');
        $this->addSql('DROP INDEX IDX_CA9EC735FA7FD7EB ON guide');
        $this->addSql('ALTER TABLE guide DROP champion_id');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231030222446 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE choix_items_ordonnees (items_group_id INT NOT NULL, ordre_items_id INT NOT NULL, INDEX IDX_DCC48A0E892F6989 (items_group_id), INDEX IDX_DCC48A0EC8F707EC (ordre_items_id), PRIMARY KEY(items_group_id, ordre_items_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE choix_items (ordre_items_id INT NOT NULL, data_item_id INT NOT NULL, INDEX IDX_299CCA8AC8F707EC (ordre_items_id), INDEX IDX_299CCA8A766404AF (data_item_id), PRIMARY KEY(ordre_items_id, data_item_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE choix_items_ordonnees ADD CONSTRAINT FK_DCC48A0E892F6989 FOREIGN KEY (items_group_id) REFERENCES items_group (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE choix_items_ordonnees ADD CONSTRAINT FK_DCC48A0EC8F707EC FOREIGN KEY (ordre_items_id) REFERENCES ordre_items (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE choix_items ADD CONSTRAINT FK_299CCA8AC8F707EC FOREIGN KEY (ordre_items_id) REFERENCES ordre_items (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE choix_items ADD CONSTRAINT FK_299CCA8A766404AF FOREIGN KEY (data_item_id) REFERENCES data_item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ensemble_items_groups ADD guide_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ensemble_items_groups ADD CONSTRAINT FK_68A1E5FBD7ED1D4B FOREIGN KEY (guide_id) REFERENCES guide (id)');
        $this->addSql('CREATE INDEX IDX_68A1E5FBD7ED1D4B ON ensemble_items_groups (guide_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE choix_items_ordonnees DROP FOREIGN KEY FK_DCC48A0E892F6989');
        $this->addSql('ALTER TABLE choix_items_ordonnees DROP FOREIGN KEY FK_DCC48A0EC8F707EC');
        $this->addSql('ALTER TABLE choix_items DROP FOREIGN KEY FK_299CCA8AC8F707EC');
        $this->addSql('ALTER TABLE choix_items DROP FOREIGN KEY FK_299CCA8A766404AF');
        $this->addSql('DROP TABLE choix_items_ordonnees');
        $this->addSql('DROP TABLE choix_items');
        $this->addSql('ALTER TABLE ensemble_items_groups DROP FOREIGN KEY FK_68A1E5FBD7ED1D4B');
        $this->addSql('DROP INDEX IDX_68A1E5FBD7ED1D4B ON ensemble_items_groups');
        $this->addSql('ALTER TABLE ensemble_items_groups DROP guide_id');
    }
}

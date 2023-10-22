<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231022204736 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE associations_arbres_runes (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE choix_runes (associations_arbres_runes_id INT NOT NULL, data_rune_id INT NOT NULL, INDEX IDX_429C6E95C49E7A84 (associations_arbres_runes_id), INDEX IDX_429C6E956A8506C0 (data_rune_id), PRIMARY KEY(associations_arbres_runes_id, data_rune_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE choix_arbres (associations_runes_bonus_id INT NOT NULL, associations_arbres_runes_id INT NOT NULL, INDEX IDX_E36C27118E20FAE3 (associations_runes_bonus_id), INDEX IDX_E36C2711C49E7A84 (associations_arbres_runes_id), PRIMARY KEY(associations_runes_bonus_id, associations_arbres_runes_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE choix_runes ADD CONSTRAINT FK_429C6E95C49E7A84 FOREIGN KEY (associations_arbres_runes_id) REFERENCES associations_arbres_runes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE choix_runes ADD CONSTRAINT FK_429C6E956A8506C0 FOREIGN KEY (data_rune_id) REFERENCES data_rune (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE choix_arbres ADD CONSTRAINT FK_E36C27118E20FAE3 FOREIGN KEY (associations_runes_bonus_id) REFERENCES associations_runes_bonus (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE choix_arbres ADD CONSTRAINT FK_E36C2711C49E7A84 FOREIGN KEY (associations_arbres_runes_id) REFERENCES associations_arbres_runes (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE choix_runes DROP FOREIGN KEY FK_429C6E95C49E7A84');
        $this->addSql('ALTER TABLE choix_runes DROP FOREIGN KEY FK_429C6E956A8506C0');
        $this->addSql('ALTER TABLE choix_arbres DROP FOREIGN KEY FK_E36C27118E20FAE3');
        $this->addSql('ALTER TABLE choix_arbres DROP FOREIGN KEY FK_E36C2711C49E7A84');
        $this->addSql('DROP TABLE associations_arbres_runes');
        $this->addSql('DROP TABLE choix_runes');
        $this->addSql('DROP TABLE choix_arbres');
    }
}

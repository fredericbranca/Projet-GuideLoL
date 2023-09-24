<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230923235105 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE data_champion (id VARCHAR(20) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE data_sort_invocateur (id VARCHAR(30) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE guide (id INT AUTO_INCREMENT NOT NULL, champion VARCHAR(20) NOT NULL, titre VARCHAR(100) NOT NULL, voie VARCHAR(10) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', modified_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sort_invocateur (id INT AUTO_INCREMENT NOT NULL, guide_id INT NOT NULL, titre VARCHAR(50) DEFAULT NULL, commentaire LONGTEXT DEFAULT NULL, ordre INT NOT NULL, INDEX IDX_CEBB8376D7ED1D4B (guide_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ChoixSortInvocateur (sort_invocateur_id INT NOT NULL, data_sort_invocateur_id VARCHAR(30) NOT NULL, INDEX IDX_25D8B9C17A207090 (sort_invocateur_id), INDEX IDX_25D8B9C150930EAF (data_sort_invocateur_id), PRIMARY KEY(sort_invocateur_id, data_sort_invocateur_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sort_invocateur ADD CONSTRAINT FK_CEBB8376D7ED1D4B FOREIGN KEY (guide_id) REFERENCES guide (id)');
        $this->addSql('ALTER TABLE ChoixSortInvocateur ADD CONSTRAINT FK_25D8B9C17A207090 FOREIGN KEY (sort_invocateur_id) REFERENCES sort_invocateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ChoixSortInvocateur ADD CONSTRAINT FK_25D8B9C150930EAF FOREIGN KEY (data_sort_invocateur_id) REFERENCES data_sort_invocateur (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sort_invocateur DROP FOREIGN KEY FK_CEBB8376D7ED1D4B');
        $this->addSql('ALTER TABLE ChoixSortInvocateur DROP FOREIGN KEY FK_25D8B9C17A207090');
        $this->addSql('ALTER TABLE ChoixSortInvocateur DROP FOREIGN KEY FK_25D8B9C150930EAF');
        $this->addSql('DROP TABLE data_champion');
        $this->addSql('DROP TABLE data_sort_invocateur');
        $this->addSql('DROP TABLE guide');
        $this->addSql('DROP TABLE sort_invocateur');
        $this->addSql('DROP TABLE ChoixSortInvocateur');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}

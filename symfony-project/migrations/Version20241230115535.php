<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241230115535 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE fiche_de_poste (id INT AUTO_INCREMENT NOT NULL, entreprise_id INT NOT NULL, titre VARCHAR(255) NOT NULL, localisation VARCHAR(255) NOT NULL, technologies JSON NOT NULL, niveau_experience INT NOT NULL, salaire_propose INT NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_C9606A6FA4AEAFEA (entreprise_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fiche_de_poste ADD CONSTRAINT FK_C9606A6FA4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES company (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fiche_de_poste DROP FOREIGN KEY FK_C9606A6FA4AEAFEA');
        $this->addSql('DROP TABLE fiche_de_poste');
    }
}

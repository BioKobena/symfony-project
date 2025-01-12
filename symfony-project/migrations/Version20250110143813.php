<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250110143813 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE company (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, nom VARCHAR(255) NOT NULL, localisation VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, taille_entreprise JSON NOT NULL, secteur JSON NOT NULL, type_entreprise JSON NOT NULL, avatar VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_4FBF094FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE developer (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, nom VARCHAR(50) NOT NULL, prenom VARCHAR(50) NOT NULL, localisation VARCHAR(100) NOT NULL, experience INT NOT NULL, salaire_min INT NOT NULL, views INT NOT NULL, bio LONGTEXT NOT NULL, avatar VARCHAR(255) NOT NULL, languages JSON NOT NULL, UNIQUE INDEX UNIQ_65FB8B9AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE favoris (id INT AUTO_INCREMENT NOT NULL, developer_id INT DEFAULT NULL, company_id INT DEFAULT NULL, fiche_de_poste_id INT DEFAULT NULL, INDEX IDX_8933C43264DD9267 (developer_id), INDEX IDX_8933C432979B1AD6 (company_id), INDEX IDX_8933C432F76AAB91 (fiche_de_poste_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fiche_de_poste (id INT AUTO_INCREMENT NOT NULL, company_id INT NOT NULL, titre VARCHAR(255) NOT NULL, localisation VARCHAR(255) NOT NULL, technologies VARCHAR(255) NOT NULL, niveau_experience INT NOT NULL, salaire_propose INT NOT NULL, description VARCHAR(255) NOT NULL, views INT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_C9606A6F979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, developer_id INT DEFAULT NULL, job_id INT DEFAULT NULL, company_id INT DEFAULT NULL, message VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_read INT NOT NULL, INDEX IDX_BF5476CA64DD9267 (developer_id), INDEX IDX_BF5476CABE04EA9 (job_id), INDEX IDX_BF5476CA979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE developer ADD CONSTRAINT FK_65FB8B9AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE favoris ADD CONSTRAINT FK_8933C43264DD9267 FOREIGN KEY (developer_id) REFERENCES developer (id)');
        $this->addSql('ALTER TABLE favoris ADD CONSTRAINT FK_8933C432979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE favoris ADD CONSTRAINT FK_8933C432F76AAB91 FOREIGN KEY (fiche_de_poste_id) REFERENCES fiche_de_poste (id)');
        $this->addSql('ALTER TABLE fiche_de_poste ADD CONSTRAINT FK_C9606A6F979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA64DD9267 FOREIGN KEY (developer_id) REFERENCES developer (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CABE04EA9 FOREIGN KEY (job_id) REFERENCES fiche_de_poste (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE company DROP FOREIGN KEY FK_4FBF094FA76ED395');
        $this->addSql('ALTER TABLE developer DROP FOREIGN KEY FK_65FB8B9AA76ED395');
        $this->addSql('ALTER TABLE favoris DROP FOREIGN KEY FK_8933C43264DD9267');
        $this->addSql('ALTER TABLE favoris DROP FOREIGN KEY FK_8933C432979B1AD6');
        $this->addSql('ALTER TABLE favoris DROP FOREIGN KEY FK_8933C432F76AAB91');
        $this->addSql('ALTER TABLE fiche_de_poste DROP FOREIGN KEY FK_C9606A6F979B1AD6');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CA64DD9267');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CABE04EA9');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CA979B1AD6');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE developer');
        $this->addSql('DROP TABLE favoris');
        $this->addSql('DROP TABLE fiche_de_poste');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE user');
    }
}

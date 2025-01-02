<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250102123125 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE favoris (id INT AUTO_INCREMENT NOT NULL, developer_id INT DEFAULT NULL, company_id INT DEFAULT NULL, fiche_de_poste_id INT DEFAULT NULL, INDEX IDX_8933C43264DD9267 (developer_id), INDEX IDX_8933C432979B1AD6 (company_id), INDEX IDX_8933C432F76AAB91 (fiche_de_poste_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE favoris ADD CONSTRAINT FK_8933C43264DD9267 FOREIGN KEY (developer_id) REFERENCES developer (id)');
        $this->addSql('ALTER TABLE favoris ADD CONSTRAINT FK_8933C432979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE favoris ADD CONSTRAINT FK_8933C432F76AAB91 FOREIGN KEY (fiche_de_poste_id) REFERENCES fiche_de_poste (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE favoris DROP FOREIGN KEY FK_8933C43264DD9267');
        $this->addSql('ALTER TABLE favoris DROP FOREIGN KEY FK_8933C432979B1AD6');
        $this->addSql('ALTER TABLE favoris DROP FOREIGN KEY FK_8933C432F76AAB91');
        $this->addSql('DROP TABLE favoris');
    }
}

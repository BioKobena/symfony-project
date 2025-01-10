<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250110112121 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fiche_de_poste DROP FOREIGN KEY FK_C9606A6FA4AEAFEA');
        $this->addSql('DROP INDEX IDX_C9606A6FA4AEAFEA ON fiche_de_poste');
        $this->addSql('ALTER TABLE fiche_de_poste CHANGE entreprise_id company_id INT NOT NULL');
        $this->addSql('ALTER TABLE fiche_de_poste ADD CONSTRAINT FK_C9606A6F979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('CREATE INDEX IDX_C9606A6F979B1AD6 ON fiche_de_poste (company_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fiche_de_poste DROP FOREIGN KEY FK_C9606A6F979B1AD6');
        $this->addSql('DROP INDEX IDX_C9606A6F979B1AD6 ON fiche_de_poste');
        $this->addSql('ALTER TABLE fiche_de_poste CHANGE company_id entreprise_id INT NOT NULL');
        $this->addSql('ALTER TABLE fiche_de_poste ADD CONSTRAINT FK_C9606A6FA4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES company (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_C9606A6FA4AEAFEA ON fiche_de_poste (entreprise_id)');
    }
}

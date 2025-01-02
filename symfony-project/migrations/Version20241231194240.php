<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241231194240 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE notification (id INT AUTO_INCREMENT NOT NULL, developer_id INT DEFAULT NULL, job_id INT DEFAULT NULL, message VARCHAR(255) NOT NULL, is_read TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_BF5476CA64DD9267 (developer_id), INDEX IDX_BF5476CABE04EA9 (job_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA64DD9267 FOREIGN KEY (developer_id) REFERENCES developer (id)');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CABE04EA9 FOREIGN KEY (job_id) REFERENCES fiche_de_poste (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CA64DD9267');
        $this->addSql('ALTER TABLE notification DROP FOREIGN KEY FK_BF5476CABE04EA9');
        $this->addSql('DROP TABLE notification');
    }
}

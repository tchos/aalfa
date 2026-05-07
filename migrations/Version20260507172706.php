<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260507172706 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE recenseur (id INT AUTO_INCREMENT NOT NULL, equipe_id INT DEFAULT NULL, nom VARCHAR(64) NOT NULL, code VARCHAR(6) NOT NULL, INDEX IDX_BFE7CB856D861B89 (equipe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE recenseur ADD CONSTRAINT FK_BFE7CB856D861B89 FOREIGN KEY (equipe_id) REFERENCES equipe (id)');
        $this->addSql('ALTER TABLE agent DROP id_agt_collecte');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recenseur DROP FOREIGN KEY FK_BFE7CB856D861B89');
        $this->addSql('DROP TABLE recenseur');
        $this->addSql('ALTER TABLE agent ADD id_agt_collecte VARCHAR(4) DEFAULT NULL');
    }
}

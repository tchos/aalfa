<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240831144437 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE enfant (id INT AUTO_INCREMENT NOT NULL, agent_id INT DEFAULT NULL, agent_saisie_id INT DEFAULT NULL, matricule VARCHAR(7) NOT NULL, rang INT NOT NULL, ordre INT NOT NULL, nom_enfant VARCHAR(64) NOT NULL, date_naissance DATE DEFAULT NULL, cec VARCHAR(128) DEFAULT NULL, numero_acte VARCHAR(32) DEFAULT NULL, date_acte_naissance DATE DEFAULT NULL, nom_conjoint VARCHAR(128) DEFAULT NULL, enfant_reconnu_y_n TINYINT(1) NOT NULL, INDEX IDX_34B70CA23414710B (agent_id), INDEX IDX_34B70CA25670B5A9 (agent_saisie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE enfant ADD CONSTRAINT FK_34B70CA23414710B FOREIGN KEY (agent_id) REFERENCES agent (id)');
        $this->addSql('ALTER TABLE enfant ADD CONSTRAINT FK_34B70CA25670B5A9 FOREIGN KEY (agent_saisie_id) REFERENCES utilisateur (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE enfant DROP FOREIGN KEY FK_34B70CA23414710B');
        $this->addSql('ALTER TABLE enfant DROP FOREIGN KEY FK_34B70CA25670B5A9');
        $this->addSql('DROP TABLE enfant');
    }
}

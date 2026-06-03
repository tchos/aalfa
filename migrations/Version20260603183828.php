<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260603183828 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE enfant ADD centre_etat_civil_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE enfant ADD CONSTRAINT FK_34B70CA27DA420AC FOREIGN KEY (centre_etat_civil_id) REFERENCES centre_etat_civil (id)');
        $this->addSql('CREATE INDEX IDX_34B70CA27DA420AC ON enfant (centre_etat_civil_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE enfant DROP FOREIGN KEY FK_34B70CA27DA420AC');
        $this->addSql('DROP INDEX IDX_34B70CA27DA420AC ON enfant');
        $this->addSql('ALTER TABLE enfant DROP centre_etat_civil_id');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260508184853 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE enfant CHANGE handicape_yn handicape_yn TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE equipe ADD coordonnateur VARCHAR(64) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE enfant CHANGE handicape_yn handicape_yn TINYINT(1) DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE equipe DROP coordonnateur');
    }
}

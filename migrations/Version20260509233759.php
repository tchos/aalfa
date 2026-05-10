<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260509233759 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2449BA1577153098 ON equipe (code)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BFE7CB8512B2DC9C ON recenseur (matricule)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_BFE7CB8512B2DC9C ON recenseur');
        $this->addSql('DROP INDEX UNIQ_2449BA1577153098 ON equipe');
    }
}

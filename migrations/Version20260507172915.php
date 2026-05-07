<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260507172915 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agent ADD recenseur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE agent ADD CONSTRAINT FK_268B9C9D97DF65BA FOREIGN KEY (recenseur_id) REFERENCES recenseur (id)');
        $this->addSql('CREATE INDEX IDX_268B9C9D97DF65BA ON agent (recenseur_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agent DROP FOREIGN KEY FK_268B9C9D97DF65BA');
        $this->addSql('DROP INDEX IDX_268B9C9D97DF65BA ON agent');
        $this->addSql('ALTER TABLE agent DROP recenseur_id');
    }
}

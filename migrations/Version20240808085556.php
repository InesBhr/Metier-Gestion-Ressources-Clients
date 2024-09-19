<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240808085556 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE 213_rejects_42l CHANGE indicateur_blocage indicateur_blocage VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE 213_rejects_42l CHANGE centre centre VARCHAR(56) DEFAULT NULL');
        $this->addSql('ALTER TABLE 213_rejects_42l ADD code_situation VARCHAR(8) DEFAULT NULL');
        $this->addSql('ALTER TABLE 213_rejects_42l CHANGE nd nd INT DEFAULT NULL');
        $this->addSql('ALTER TABLE 213_rejects_42l CHANGE nd nd VARCHAR(56) DEFAULT NULL, CHANGE ne ne VARCHAR(56) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE 213_rejects_42l CHANGE indicateur_blocage indicateur_blocage VARCHAR(16) DEFAULT NULL');
        $this->addSql('ALTER TABLE 213_rejects_42l CHANGE centre centre VARCHAR(8) DEFAULT NULL');
        $this->addSql('ALTER TABLE 213_rejects_42l DROP code_situation');
        $this->addSql('ALTER TABLE 213_rejects_42l CHANGE nd nd INT NOT NULL');
        $this->addSql('ALTER TABLE 213_rejects_42l CHANGE nd nd INT DEFAULT NULL, CHANGE ne ne INT DEFAULT NULL');
    }
}

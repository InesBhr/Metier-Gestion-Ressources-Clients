<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240805145004 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE 213_rejects_42l ADD reject_state_id INT NOT NULL, ADD date_traitement DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE 213_rejects_42l ADD CONSTRAINT FK_3C56DB1032E995ED FOREIGN KEY (reject_state_id) REFERENCES 201_anomalie_state (id)');
        $this->addSql('CREATE INDEX IDX_3C56DB1032E995ED ON 213_rejects_42l (reject_state_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE 213_rejects_42l DROP FOREIGN KEY FK_3C56DB1032E995ED');
        $this->addSql('DROP INDEX IDX_3C56DB1032E995ED ON 213_rejects_42l');
        $this->addSql('ALTER TABLE 213_rejects_42l DROP reject_state_id, DROP date_traitement');
    }
}

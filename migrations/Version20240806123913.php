<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240806123913 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE 213_rejects_42l DROP FOREIGN KEY FK_3C56DB1032E995ED');
        $this->addSql('DROP INDEX idx_3c56db1032e995ed ON 213_rejects_42l');
        $this->addSql('CREATE INDEX IDX_3C56DB10EAF962A ON 213_rejects_42l (reject_state_id)');
        $this->addSql('ALTER TABLE 213_rejects_42l ADD CONSTRAINT FK_3C56DB1032E995ED FOREIGN KEY (reject_state_id) REFERENCES 201_anomalie_state (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE 213_rejects_42l DROP FOREIGN KEY FK_3C56DB10EAF962A');
        $this->addSql('DROP INDEX idx_3c56db10eaf962a ON 213_rejects_42l');
        $this->addSql('CREATE INDEX IDX_3C56DB1032E995ED ON 213_rejects_42l (reject_state_id)');
        $this->addSql('ALTER TABLE 213_rejects_42l ADD CONSTRAINT FK_3C56DB10EAF962A FOREIGN KEY (reject_state_id) REFERENCES 201_anomalie_state (id)');
    }
}

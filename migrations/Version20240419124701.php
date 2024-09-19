<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240419124701 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE rejects42_c (id INT AUTO_INCREMENT NOT NULL, reject_state_id INT NOT NULL, base VARCHAR(16) DEFAULT NULL, date_rejet DATE DEFAULT NULL, infos_site VARCHAR(48) DEFAULT NULL, operation VARCHAR(255) DEFAULT NULL, date_traitement DATE DEFAULT NULL, INDEX IDX_8EE09CCEEAF962A (reject_state_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE rejects42_c ADD CONSTRAINT FK_8EE09CCEEAF962A FOREIGN KEY (reject_state_id) REFERENCES 201_anomalie_state (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rejects42_c DROP FOREIGN KEY FK_8EE09CCEEAF962A');
        $this->addSql('DROP TABLE rejects42_c');
    }
}

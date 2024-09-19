<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240329144912 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Workflow tables';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE 200_anomalie_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('INSERT INTO 200_anomalie_type (id, name) VALUES (1, "Dispo")');
        $this->addSql('INSERT INTO 200_anomalie_type (id, name) VALUES (2, "Interdit")');
        $this->addSql('CREATE TABLE 201_anomalie_state (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('INSERT INTO 201_anomalie_state (id, name) VALUES (1, "ATraiter")');
        $this->addSql('INSERT INTO 201_anomalie_state (id, name) VALUES (2, "Traité")');
        $this->addSql('INSERT INTO 201_anomalie_state (id, name) VALUES (3, "NonTraité")');
        $this->addSql('CREATE TABLE 210_anomalies_ban (id INT AUTO_INCREMENT NOT NULL, anomalie_state_id INT NOT NULL, upr VARCHAR(50) DEFAULT NULL, code_ban VARCHAR(50) DEFAULT NULL, code42_c VARCHAR(50) DEFAULT NULL, nra VARCHAR(50) DEFAULT NULL, sgtqs VARCHAR(50) DEFAULT NULL, nd INT NOT NULL, type_porta VARCHAR(50) NOT NULL, crn VARCHAR(50) DEFAULT NULL, op INT DEFAULT NULL, date_porta DATETIME DEFAULT NULL, INDEX IDX_9B2AA1626854704 (anomalie_state_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE 211_anomalies_spn (id INT AUTO_INCREMENT NOT NULL, anomalie_type_id INT NOT NULL, anomalie_state_id INT NOT NULL, upr VARCHAR(50) DEFAULT NULL, code_serveur42_l VARCHAR(50) DEFAULT NULL, nd INT NOT NULL, type_porta VARCHAR(50) DEFAULT NULL, crn VARCHAR(50) DEFAULT NULL, z0bpq INT DEFAULT NULL, date_porta DATETIME DEFAULT NULL, INDEX IDX_FE749BA94150F664 (anomalie_type_id), INDEX IDX_FE749BA926854704 (anomalie_state_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE 210_anomalies_ban ADD CONSTRAINT FK_9B2AA1626854704 FOREIGN KEY (anomalie_state_id) REFERENCES 201_anomalie_state (id)');
        $this->addSql('ALTER TABLE 211_anomalies_spn ADD CONSTRAINT FK_FE749BA94150F664 FOREIGN KEY (anomalie_type_id) REFERENCES 200_anomalie_type (id)');
        $this->addSql('ALTER TABLE 211_anomalies_spn ADD CONSTRAINT FK_FE749BA926854704 FOREIGN KEY (anomalie_state_id) REFERENCES 201_anomalie_state (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE 210_anomalies_ban DROP FOREIGN KEY FK_9B2AA1626854704');
        $this->addSql('ALTER TABLE 211_anomalies_spn DROP FOREIGN KEY FK_FE749BA94150F664');
        $this->addSql('ALTER TABLE 211_anomalies_spn DROP FOREIGN KEY FK_FE749BA926854704');
        $this->addSql('DROP TABLE 200_anomalie_type');
        $this->addSql('DROP TABLE 201_anomalie_state');
        $this->addSql('DROP TABLE 210_anomalies_ban');
        $this->addSql('DROP TABLE 211_anomalies_spn');
    }
}

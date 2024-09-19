<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240805115944 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE 213_rejects_42l (id INT AUTO_INCREMENT NOT NULL, dr VARCHAR(8) DEFAULT NULL, date_mvt VARCHAR(50) DEFAULT NULL, centre VARCHAR(8) DEFAULT NULL, nd INT NOT NULL, ne INT DEFAULT NULL, code_mouvement VARCHAR(4) DEFAULT NULL, repartiteur VARCHAR(8) DEFAULT NULL, type_ne VARCHAR(8) DEFAULT NULL, numero_mvt INT DEFAULT NULL, ancien_nd INT DEFAULT NULL, code_adresse_rattachement VARCHAR(8) DEFAULT NULL, code_operateur_concurrent VARCHAR(8) DEFAULT NULL, type_portabilite VARCHAR(16) DEFAULT NULL, indicateur_blocage VARCHAR(16) DEFAULT NULL, info1 VARCHAR(255) DEFAULT NULL, info2 VARCHAR(255) DEFAULT NULL, a054 VARCHAR(32) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE 213_rejects_42l (id INT AUTO_INCREMENT NOT NULL, dr VARCHAR(8) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, date_mvt VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, centre VARCHAR(8) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, nd INT NOT NULL, ne INT DEFAULT NULL, code_mouvement VARCHAR(4) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, repartiteur VARCHAR(8) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, type_ne VARCHAR(8) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, numero_mvt INT DEFAULT NULL, ancien_nd INT DEFAULT NULL, code_adresse_rattachement VARCHAR(8) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, code_operateur_concurrent VARCHAR(8) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, type_portabilite VARCHAR(16) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, indicateur_blocage VARCHAR(16) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, info1 VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, info2 VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, a054 VARCHAR(32) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE 213_rejects_42l');
    }
}

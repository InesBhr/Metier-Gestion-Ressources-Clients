<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240329144911 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Documentation tables';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs

        $this->addSql('CREATE TABLE 101_fileupload (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, size INT DEFAULT NULL, key_name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, rights VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE 110_program (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, key_name VARCHAR(255) NOT NULL, rights VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE 111_documentation_program (id INT AUTO_INCREMENT NOT NULL, program_id_id INT NOT NULL, documentation VARCHAR(255) DEFAULT NULL, version INT DEFAULT NULL, INDEX IDX_D2969FF2E12DEDA1 (program_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE 111_documentation_program ADD CONSTRAINT FK_D2969FF2E12DEDA1 FOREIGN KEY (program_id_id) REFERENCES 110_program (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE 111_documentation_program DROP FOREIGN KEY FK_D2969FF2E12DEDA1');
        $this->addSql('DROP TABLE 101_fileupload');
        $this->addSql('DROP TABLE 110_program');
        $this->addSql('DROP TABLE 111_documentation_program');
    }
}

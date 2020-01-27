<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200127123556 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE student (id INT AUTO_INCREMENT NOT NULL, klas_id INT DEFAULT NULL, naam VARCHAR(255) NOT NULL, student_id VARCHAR(255) NOT NULL, INDEX IDX_B723AF332F3345ED (klas_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF332F3345ED FOREIGN KEY (klas_id) REFERENCES klas (id)');
        $this->addSql('ALTER TABLE afspraak CHANGE uitnodiging_id uitnodiging_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE klas DROP leerlingen, CHANGE slb_id slb_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE uitnodiging CHANGE klas_id klas_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE student');
        $this->addSql('ALTER TABLE afspraak CHANGE uitnodiging_id uitnodiging_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE klas ADD leerlingen LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:array)\', CHANGE slb_id slb_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE uitnodiging CHANGE klas_id klas_id INT DEFAULT NULL');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200127230432 extends AbstractMigration
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
        $this->addSql('ALTER TABLE afspraak ADD student_id INT DEFAULT NULL, DROP student_number, CHANGE uitnodiging_id uitnodiging_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE afspraak ADD CONSTRAINT FK_CBC4B205CB944F1A FOREIGN KEY (student_id) REFERENCES student (id)');
        $this->addSql('CREATE INDEX IDX_CBC4B205CB944F1A ON afspraak (student_id)');
        $this->addSql('ALTER TABLE klas DROP leerlingen, CHANGE slb_id slb_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE uitnodiging CHANGE klas_id klas_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE afspraak DROP FOREIGN KEY FK_CBC4B205CB944F1A');
        $this->addSql('DROP TABLE student');
        $this->addSql('DROP INDEX IDX_CBC4B205CB944F1A ON afspraak');
        $this->addSql('ALTER TABLE afspraak ADD student_number VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP student_id, CHANGE uitnodiging_id uitnodiging_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE klas ADD leerlingen LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:array)\', CHANGE slb_id slb_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE uitnodiging CHANGE klas_id klas_id INT DEFAULT NULL');
    }
}

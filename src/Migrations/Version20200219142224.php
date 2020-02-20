<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200219142224 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE afspraak CHANGE uitnodiging_id uitnodiging_id INT DEFAULT NULL, CHANGE student_id student_id INT DEFAULT NULL, CHANGE with_parents met_ouders TINYINT(1) NOT NULL, CHANGE time tijd TIME NOT NULL, CHANGE phone_number telefoon_nummer VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE klas CHANGE slb_id slb_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE student CHANGE klas_id klas_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE uitnodiging CHANGE klas_id klas_id INT DEFAULT NULL, CHANGE invitation_code uitnodigings_code VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE location_id location_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE afspraak CHANGE uitnodiging_id uitnodiging_id INT DEFAULT NULL, CHANGE student_id student_id INT DEFAULT NULL, CHANGE met_ouders with_parents TINYINT(1) NOT NULL, CHANGE tijd time TIME NOT NULL, CHANGE telefoon_nummer phone_number VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE klas CHANGE slb_id slb_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE student CHANGE klas_id klas_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE uitnodiging CHANGE klas_id klas_id INT DEFAULT NULL, CHANGE uitnodigings_code invitation_code VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user CHANGE location_id location_id INT DEFAULT NULL');
    }
}

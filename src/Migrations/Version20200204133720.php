<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200204133720 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE afspraak (id INT AUTO_INCREMENT NOT NULL, uitnodiging_id INT DEFAULT NULL, student_id INT DEFAULT NULL, with_parents TINYINT(1) NOT NULL, time TIME NOT NULL, phone_number VARCHAR(255) NOT NULL, INDEX IDX_CBC4B20585217164 (uitnodiging_id), INDEX IDX_CBC4B205CB944F1A (student_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE klas (id INT AUTO_INCREMENT NOT NULL, slb_id INT DEFAULT NULL, location_id INT NOT NULL, naam VARCHAR(255) NOT NULL, INDEX IDX_3944E73A6899672A (slb_id), INDEX IDX_3944E73A64D218E (location_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE location (id INT AUTO_INCREMENT NOT NULL, naam VARCHAR(255) NOT NULL, adres VARCHAR(255) NOT NULL, directeur VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE student (id INT AUTO_INCREMENT NOT NULL, klas_id INT DEFAULT NULL, naam VARCHAR(255) NOT NULL, student_id VARCHAR(255) NOT NULL, INDEX IDX_B723AF332F3345ED (klas_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE uitnodiging (id INT AUTO_INCREMENT NOT NULL, klas_id INT DEFAULT NULL, invitation_code VARCHAR(255) NOT NULL, start_time TIME NOT NULL, stop_time TIME NOT NULL, date DATE NOT NULL, INDEX IDX_12C8A90C2F3345ED (klas_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', username VARCHAR(255) NOT NULL, is_admin TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE afspraak ADD CONSTRAINT FK_CBC4B20585217164 FOREIGN KEY (uitnodiging_id) REFERENCES uitnodiging (id)');
        $this->addSql('ALTER TABLE afspraak ADD CONSTRAINT FK_CBC4B205CB944F1A FOREIGN KEY (student_id) REFERENCES student (id)');
        $this->addSql('ALTER TABLE klas ADD CONSTRAINT FK_3944E73A6899672A FOREIGN KEY (slb_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE klas ADD CONSTRAINT FK_3944E73A64D218E FOREIGN KEY (location_id) REFERENCES location (id)');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF332F3345ED FOREIGN KEY (klas_id) REFERENCES klas (id)');
        $this->addSql('ALTER TABLE uitnodiging ADD CONSTRAINT FK_12C8A90C2F3345ED FOREIGN KEY (klas_id) REFERENCES klas (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF332F3345ED');
        $this->addSql('ALTER TABLE uitnodiging DROP FOREIGN KEY FK_12C8A90C2F3345ED');
        $this->addSql('ALTER TABLE klas DROP FOREIGN KEY FK_3944E73A64D218E');
        $this->addSql('ALTER TABLE afspraak DROP FOREIGN KEY FK_CBC4B205CB944F1A');
        $this->addSql('ALTER TABLE afspraak DROP FOREIGN KEY FK_CBC4B20585217164');
        $this->addSql('ALTER TABLE klas DROP FOREIGN KEY FK_3944E73A6899672A');
        $this->addSql('DROP TABLE afspraak');
        $this->addSql('DROP TABLE klas');
        $this->addSql('DROP TABLE location');
        $this->addSql('DROP TABLE student');
        $this->addSql('DROP TABLE uitnodiging');
        $this->addSql('DROP TABLE user');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200126224059 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE afspraak CHANGE uitnodiging_id uitnodiging_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE afspraak ADD CONSTRAINT FK_CBC4B20585217164 FOREIGN KEY (uitnodiging_id) REFERENCES uitnodiging (id)');
        $this->addSql('ALTER TABLE klas ADD naam VARCHAR(255) NOT NULL, CHANGE slb_id slb_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE klas ADD CONSTRAINT FK_3944E73A6899672A FOREIGN KEY (slb_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE uitnodiging CHANGE klas_id klas_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE uitnodiging ADD CONSTRAINT FK_12C8A90C2F3345ED FOREIGN KEY (klas_id) REFERENCES klas (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE afspraak DROP FOREIGN KEY FK_CBC4B20585217164');
        $this->addSql('ALTER TABLE afspraak CHANGE uitnodiging_id uitnodiging_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE klas DROP FOREIGN KEY FK_3944E73A6899672A');
        $this->addSql('ALTER TABLE klas DROP naam, CHANGE slb_id slb_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE uitnodiging DROP FOREIGN KEY FK_12C8A90C2F3345ED');
        $this->addSql('ALTER TABLE uitnodiging CHANGE klas_id klas_id INT DEFAULT NULL');
    }
}

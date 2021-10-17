<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211013110849 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE advisor (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(80) NOT NULL, description LONGTEXT DEFAULT NULL, availability TINYINT(1) DEFAULT \'0\' NOT NULL, price_per_minute NUMERIC(15, 2) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE advisor_languages (id INT AUTO_INCREMENT NOT NULL, advisor_id INT DEFAULT NULL, language_code VARCHAR(3) NOT NULL, INDEX IDX_238040D466D3AD77 (advisor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE advisor_languages ADD CONSTRAINT FK_238040D466D3AD77 FOREIGN KEY (advisor_id) REFERENCES advisor (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE advisor_languages DROP FOREIGN KEY FK_238040D466D3AD77');
        $this->addSql('DROP TABLE advisor');
        $this->addSql('DROP TABLE advisor_languages');
    }
}
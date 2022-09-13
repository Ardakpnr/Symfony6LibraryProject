<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220912201121 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE setting CHANGE keywords keywords VARCHAR(255) DEFAULT NULL, CHANGE desceription desceription VARCHAR(255) DEFAULT NULL, CHANGE company company VARCHAR(100) DEFAULT NULL, CHANGE adress adress VARCHAR(255) DEFAULT NULL, CHANGE phone phone VARCHAR(15) DEFAULT NULL, CHANGE fax fax VARCHAR(15) DEFAULT NULL, CHANGE email email VARCHAR(50) DEFAULT NULL, CHANGE instegram instegram VARCHAR(100) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE setting CHANGE keywords keywords VARCHAR(255) NOT NULL, CHANGE desceription desceription VARCHAR(255) NOT NULL, CHANGE company company VARCHAR(100) NOT NULL, CHANGE adress adress VARCHAR(255) NOT NULL, CHANGE phone phone VARCHAR(15) NOT NULL, CHANGE fax fax VARCHAR(14) NOT NULL, CHANGE email email VARCHAR(50) NOT NULL, CHANGE instegram instegram VARCHAR(100) NOT NULL');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240521092918 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ship (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, mass INT DEFAULT NULL, cargo_capacity INT DEFAULT NULL, hp INT DEFAULT NULL, hp_shield INT DEFAULT NULL, speed_scm INT DEFAULT NULL, speed_max INT DEFAULT NULL, speed_quantum INT DEFAULT NULL, role VARCHAR(255) DEFAULT NULL, description VARCHAR(1024) DEFAULT NULL, size INT NOT NULL, manufacturer VARCHAR(255) NOT NULL, irl_price INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE ship');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230927094752 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE producto (id INT AUTO_INCREMENT NOT NULL, seccion_id INT NOT NULL, nombre VARCHAR(255) NOT NULL, precio DOUBLE PRECISION NOT NULL, foto VARCHAR(255) NOT NULL, INDEX IDX_A7BB06157A5A413A (seccion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE seccion (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE producto ADD CONSTRAINT FK_A7BB06157A5A413A FOREIGN KEY (seccion_id) REFERENCES seccion (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE producto DROP FOREIGN KEY FK_A7BB06157A5A413A');
        $this->addSql('DROP TABLE producto');
        $this->addSql('DROP TABLE seccion');
    }
}

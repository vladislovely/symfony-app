<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250205131617 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE standard_unit_value (id SERIAL NOT NULL, uve_id INT NOT NULL, number VARCHAR(255) NOT NULL, title VARCHAR(512) NOT NULL, act_no VARCHAR(128) DEFAULT NULL, act_date DATE DEFAULT NULL, interval INT DEFAULT NULL, created_at DATE NOT NULL, updated_at DATE NOT NULL, created_by INT NOT NULL, updated_by INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN standard_unit_value.act_date IS \'(DC2Type:date_immutable)\'');
        $this->addSql('COMMENT ON COLUMN standard_unit_value.created_at IS \'(DC2Type:date_immutable)\'');
        $this->addSql('COMMENT ON COLUMN standard_unit_value.updated_at IS \'(DC2Type:date_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE standard_unit_value');
    }
}

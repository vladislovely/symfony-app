<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250206115830 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE grade_standard (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, short_name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE standard_state_primary (id SERIAL NOT NULL, npe_id INT NOT NULL, number VARCHAR(32) NOT NULL, type VARCHAR(32) NOT NULL, title VARCHAR(512) NOT NULL, institute VARCHAR(512) DEFAULT NULL, certification_year INT DEFAULT NULL, approval_year INT DEFAULT NULL, status VARCHAR(32) DEFAULT NULL, created_at DATE NOT NULL, updated_at DATE NOT NULL, deleted_at DATE DEFAULT NULL, created_by INT NOT NULL, updated_by INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN standard_state_primary.created_at IS \'(DC2Type:date_immutable)\'');
        $this->addSql('COMMENT ON COLUMN standard_state_primary.updated_at IS \'(DC2Type:date_immutable)\'');
        $this->addSql('COMMENT ON COLUMN standard_state_primary.deleted_at IS \'(DC2Type:date_immutable)\'');
        $this->addSql('CREATE TABLE standard_unit_value_grade (id SERIAL NOT NULL, standard_unit_value_id INT NOT NULL, grade_standard_id INT NOT NULL, order_id INT NOT NULL, created_at DATE NOT NULL, updated_at DATE NOT NULL, deleted_at DATE DEFAULT NULL, created_by INT NOT NULL, updated_by INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN standard_unit_value_grade.created_at IS \'(DC2Type:date_immutable)\'');
        $this->addSql('COMMENT ON COLUMN standard_unit_value_grade.updated_at IS \'(DC2Type:date_immutable)\'');
        $this->addSql('COMMENT ON COLUMN standard_unit_value_grade.deleted_at IS \'(DC2Type:date_immutable)\'');
        $this->addSql('CREATE TABLE standard_unit_value_npe (id SERIAL NOT NULL, standard_unit_value_id INT NOT NULL, standard_state_primary_id INT NOT NULL, order_id INT NOT NULL, created_at DATE NOT NULL, updated_at DATE NOT NULL, deleted_at DATE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN standard_unit_value_npe.created_at IS \'(DC2Type:date_immutable)\'');
        $this->addSql('COMMENT ON COLUMN standard_unit_value_npe.updated_at IS \'(DC2Type:date_immutable)\'');
        $this->addSql('COMMENT ON COLUMN standard_unit_value_npe.deleted_at IS \'(DC2Type:date_immutable)\'');
        $this->addSql('ALTER TABLE standard_unit_value ALTER title TYPE VARCHAR(1000)');
        $this->addSql('ALTER TABLE standard_unit_value ALTER created_by DROP DEFAULT');
        $this->addSql('ALTER TABLE standard_unit_value ALTER updated_by DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE grade_standard');
        $this->addSql('DROP TABLE standard_state_primary');
        $this->addSql('DROP TABLE standard_unit_value_grade');
        $this->addSql('DROP TABLE standard_unit_value_npe');
        $this->addSql('ALTER TABLE standard_unit_value ALTER title TYPE VARCHAR(999)');
        $this->addSql('ALTER TABLE standard_unit_value ALTER created_by SET DEFAULT 1');
        $this->addSql('ALTER TABLE standard_unit_value ALTER updated_by SET DEFAULT 1');
    }
}

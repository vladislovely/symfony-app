<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230706102420 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book_account DROP CONSTRAINT fk_ee167e3216a2b381');
        $this->addSql('ALTER TABLE book_account DROP CONSTRAINT fk_ee167e329b6b5fba');
        $this->addSql('DROP TABLE book_account');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE TABLE book_account (book_id UUID NOT NULL, account_id UUID NOT NULL, PRIMARY KEY(book_id, account_id))');
        $this->addSql('CREATE INDEX idx_ee167e329b6b5fba ON book_account (account_id)');
        $this->addSql('CREATE INDEX idx_ee167e3216a2b381 ON book_account (book_id)');
        $this->addSql('COMMENT ON COLUMN book_account.book_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN book_account.account_id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE book_account ADD CONSTRAINT fk_ee167e3216a2b381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE book_account ADD CONSTRAINT fk_ee167e329b6b5fba FOREIGN KEY (account_id) REFERENCES account (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}

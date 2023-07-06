<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230706095643 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE account (id UUID NOT NULL, first_name VARCHAR(100) NOT NULL, surname VARCHAR(100) NOT NULL, email VARCHAR(255) NOT NULL, status BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN account.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE account_book (account_id UUID NOT NULL, book_id UUID NOT NULL, PRIMARY KEY(account_id, book_id))');
        $this->addSql('CREATE INDEX IDX_4776249B9B6B5FBA ON account_book (account_id)');
        $this->addSql('CREATE INDEX IDX_4776249B16A2B381 ON account_book (book_id)');
        $this->addSql('COMMENT ON COLUMN account_book.account_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN account_book.book_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE author (id UUID NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN author.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE book (id UUID NOT NULL, category_id UUID DEFAULT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CBE5A3312B36786B ON book (title)');
        $this->addSql('CREATE INDEX IDX_CBE5A33112469DE2 ON book (category_id)');
        $this->addSql('COMMENT ON COLUMN book.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN book.category_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE book_author (book_id UUID NOT NULL, author_id UUID NOT NULL, PRIMARY KEY(book_id, author_id))');
        $this->addSql('CREATE INDEX IDX_9478D34516A2B381 ON book_author (book_id)');
        $this->addSql('CREATE INDEX IDX_9478D345F675F31B ON book_author (author_id)');
        $this->addSql('COMMENT ON COLUMN book_author.book_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN book_author.author_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE book_account (book_id UUID NOT NULL, account_id UUID NOT NULL, PRIMARY KEY(book_id, account_id))');
        $this->addSql('CREATE INDEX IDX_EE167E3216A2B381 ON book_account (book_id)');
        $this->addSql('CREATE INDEX IDX_EE167E329B6B5FBA ON book_account (account_id)');
        $this->addSql('COMMENT ON COLUMN book_account.book_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN book_account.account_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE book_copy (id UUID NOT NULL, book_id UUID DEFAULT NULL, publisher_id UUID DEFAULT NULL, year_published SMALLINT NOT NULL, count SMALLINT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5427F08A16A2B381 ON book_copy (book_id)');
        $this->addSql('CREATE INDEX IDX_5427F08A40C86FCE ON book_copy (publisher_id)');
        $this->addSql('COMMENT ON COLUMN book_copy.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN book_copy.book_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN book_copy.publisher_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE category (id UUID NOT NULL, title VARCHAR(100) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN category.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE checkout (id UUID NOT NULL, book_copy_id UUID DEFAULT NULL, account_id UUID DEFAULT NULL, start_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, end_time TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, is_returned BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_AF382D4E3B550FE4 ON checkout (book_copy_id)');
        $this->addSql('CREATE INDEX IDX_AF382D4E9B6B5FBA ON checkout (account_id)');
        $this->addSql('COMMENT ON COLUMN checkout.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN checkout.book_copy_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN checkout.account_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN checkout.start_time IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN checkout.end_time IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE hold (id UUID NOT NULL, book_copy_id UUID DEFAULT NULL, account_id UUID DEFAULT NULL, start_time TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, end_time TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1FCA0D073B550FE4 ON hold (book_copy_id)');
        $this->addSql('CREATE INDEX IDX_1FCA0D079B6B5FBA ON hold (account_id)');
        $this->addSql('COMMENT ON COLUMN hold.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN hold.book_copy_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN hold.account_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN hold.start_time IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN hold.end_time IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE notification (id UUID NOT NULL, account_id UUID DEFAULT NULL, sent_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, type VARCHAR(50) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_BF5476CA9B6B5FBA ON notification (account_id)');
        $this->addSql('COMMENT ON COLUMN notification.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN notification.account_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN notification.sent_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE publisher (id UUID NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9CE8D5462B36786B ON publisher (title)');
        $this->addSql('COMMENT ON COLUMN publisher.id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE account_book ADD CONSTRAINT FK_4776249B9B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE account_book ADD CONSTRAINT FK_4776249B16A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE book ADD CONSTRAINT FK_CBE5A33112469DE2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE book_author ADD CONSTRAINT FK_9478D34516A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE book_author ADD CONSTRAINT FK_9478D345F675F31B FOREIGN KEY (author_id) REFERENCES author (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE book_account ADD CONSTRAINT FK_EE167E3216A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE book_account ADD CONSTRAINT FK_EE167E329B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE book_copy ADD CONSTRAINT FK_5427F08A16A2B381 FOREIGN KEY (book_id) REFERENCES book (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE book_copy ADD CONSTRAINT FK_5427F08A40C86FCE FOREIGN KEY (publisher_id) REFERENCES publisher (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE checkout ADD CONSTRAINT FK_AF382D4E3B550FE4 FOREIGN KEY (book_copy_id) REFERENCES book_copy (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE checkout ADD CONSTRAINT FK_AF382D4E9B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE hold ADD CONSTRAINT FK_1FCA0D073B550FE4 FOREIGN KEY (book_copy_id) REFERENCES book_copy (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE hold ADD CONSTRAINT FK_1FCA0D079B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE notification ADD CONSTRAINT FK_BF5476CA9B6B5FBA FOREIGN KEY (account_id) REFERENCES account (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE account_book DROP CONSTRAINT FK_4776249B9B6B5FBA');
        $this->addSql('ALTER TABLE account_book DROP CONSTRAINT FK_4776249B16A2B381');
        $this->addSql('ALTER TABLE book DROP CONSTRAINT FK_CBE5A33112469DE2');
        $this->addSql('ALTER TABLE book_author DROP CONSTRAINT FK_9478D34516A2B381');
        $this->addSql('ALTER TABLE book_author DROP CONSTRAINT FK_9478D345F675F31B');
        $this->addSql('ALTER TABLE book_account DROP CONSTRAINT FK_EE167E3216A2B381');
        $this->addSql('ALTER TABLE book_account DROP CONSTRAINT FK_EE167E329B6B5FBA');
        $this->addSql('ALTER TABLE book_copy DROP CONSTRAINT FK_5427F08A16A2B381');
        $this->addSql('ALTER TABLE book_copy DROP CONSTRAINT FK_5427F08A40C86FCE');
        $this->addSql('ALTER TABLE checkout DROP CONSTRAINT FK_AF382D4E3B550FE4');
        $this->addSql('ALTER TABLE checkout DROP CONSTRAINT FK_AF382D4E9B6B5FBA');
        $this->addSql('ALTER TABLE hold DROP CONSTRAINT FK_1FCA0D073B550FE4');
        $this->addSql('ALTER TABLE hold DROP CONSTRAINT FK_1FCA0D079B6B5FBA');
        $this->addSql('ALTER TABLE notification DROP CONSTRAINT FK_BF5476CA9B6B5FBA');
        $this->addSql('DROP TABLE account');
        $this->addSql('DROP TABLE account_book');
        $this->addSql('DROP TABLE author');
        $this->addSql('DROP TABLE book');
        $this->addSql('DROP TABLE book_author');
        $this->addSql('DROP TABLE book_account');
        $this->addSql('DROP TABLE book_copy');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE checkout');
        $this->addSql('DROP TABLE hold');
        $this->addSql('DROP TABLE notification');
        $this->addSql('DROP TABLE publisher');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220112175847 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE articles (id INT AUTO_INCREMENT NOT NULL, author_id_id INT NOT NULL, title VARCHAR(100) NOT NULL, published_date DATETIME NOT NULL, content LONGTEXT NOT NULL, creation_date DATETIME NOT NULL, last_modified DATETIME NOT NULL, INDEX IDX_BFDD316869CCBE9A (author_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE author (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(50) NOT NULL, last_name VARCHAR(50) NOT NULL, email VARCHAR(50) NOT NULL, password VARCHAR(50) NOT NULL, registered_date DATETIME NOT NULL, last_login DATETIME NOT NULL, intro LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comments (id INT AUTO_INCREMENT NOT NULL, article_id_id INT NOT NULL, content LONGTEXT NOT NULL, published DATETIME NOT NULL, last_modified DATETIME DEFAULT NULL, INDEX IDX_5F9E962A8F3EC46 (article_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post_tags (id INT AUTO_INCREMENT NOT NULL, tag_id_id INT NOT NULL, UNIQUE INDEX UNIQ_A6E9F32D5DA88751 (tag_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE post_tags_articles (post_tags_id INT NOT NULL, articles_id INT NOT NULL, INDEX IDX_CA3A1362B093F887 (post_tags_id), INDEX IDX_CA3A13621EBAF6CC (articles_id), PRIMARY KEY(post_tags_id, articles_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tags (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, creation_date DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE articles ADD CONSTRAINT FK_BFDD316869CCBE9A FOREIGN KEY (author_id_id) REFERENCES author (id)');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962A8F3EC46 FOREIGN KEY (article_id_id) REFERENCES articles (id)');
        $this->addSql('ALTER TABLE post_tags ADD CONSTRAINT FK_A6E9F32D5DA88751 FOREIGN KEY (tag_id_id) REFERENCES tags (id)');
        $this->addSql('ALTER TABLE post_tags_articles ADD CONSTRAINT FK_CA3A1362B093F887 FOREIGN KEY (post_tags_id) REFERENCES post_tags (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post_tags_articles ADD CONSTRAINT FK_CA3A13621EBAF6CC FOREIGN KEY (articles_id) REFERENCES articles (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962A8F3EC46');
        $this->addSql('ALTER TABLE post_tags_articles DROP FOREIGN KEY FK_CA3A13621EBAF6CC');
        $this->addSql('ALTER TABLE articles DROP FOREIGN KEY FK_BFDD316869CCBE9A');
        $this->addSql('ALTER TABLE post_tags_articles DROP FOREIGN KEY FK_CA3A1362B093F887');
        $this->addSql('ALTER TABLE post_tags DROP FOREIGN KEY FK_A6E9F32D5DA88751');
        $this->addSql('DROP TABLE articles');
        $this->addSql('DROP TABLE author');
        $this->addSql('DROP TABLE comments');
        $this->addSql('DROP TABLE post_tags');
        $this->addSql('DROP TABLE post_tags_articles');
        $this->addSql('DROP TABLE tags');
    }
}

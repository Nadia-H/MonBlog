<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220112224438 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE post_tags_articles DROP FOREIGN KEY FK_CA3A1362B093F887');
        $this->addSql('CREATE TABLE article_tags (id INT AUTO_INCREMENT NOT NULL, tag_id_id INT NOT NULL, UNIQUE INDEX UNIQ_DFFE13275DA88751 (tag_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article_tags_articles (article_tags_id INT NOT NULL, articles_id INT NOT NULL, INDEX IDX_529042D460A90B03 (article_tags_id), INDEX IDX_529042D41EBAF6CC (articles_id), PRIMARY KEY(article_tags_id, articles_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, confirm_password VARCHAR(50) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article_tags ADD CONSTRAINT FK_DFFE13275DA88751 FOREIGN KEY (tag_id_id) REFERENCES tags (id)');
        $this->addSql('ALTER TABLE article_tags_articles ADD CONSTRAINT FK_529042D460A90B03 FOREIGN KEY (article_tags_id) REFERENCES article_tags (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article_tags_articles ADD CONSTRAINT FK_529042D41EBAF6CC FOREIGN KEY (articles_id) REFERENCES articles (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE post_tags');
        $this->addSql('DROP TABLE post_tags_articles');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article_tags_articles DROP FOREIGN KEY FK_529042D460A90B03');
        $this->addSql('CREATE TABLE post_tags (id INT AUTO_INCREMENT NOT NULL, tag_id_id INT NOT NULL, UNIQUE INDEX UNIQ_A6E9F32D5DA88751 (tag_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE post_tags_articles (post_tags_id INT NOT NULL, articles_id INT NOT NULL, INDEX IDX_CA3A13621EBAF6CC (articles_id), INDEX IDX_CA3A1362B093F887 (post_tags_id), PRIMARY KEY(post_tags_id, articles_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE post_tags ADD CONSTRAINT FK_A6E9F32D5DA88751 FOREIGN KEY (tag_id_id) REFERENCES tags (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE post_tags_articles ADD CONSTRAINT FK_CA3A13621EBAF6CC FOREIGN KEY (articles_id) REFERENCES articles (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE post_tags_articles ADD CONSTRAINT FK_CA3A1362B093F887 FOREIGN KEY (post_tags_id) REFERENCES post_tags (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('DROP TABLE article_tags');
        $this->addSql('DROP TABLE article_tags_articles');
        $this->addSql('DROP TABLE user');
    }
}

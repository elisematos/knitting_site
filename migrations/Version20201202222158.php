<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201202222158 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE brand (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, pattern_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_C53D045FF734A20F (pattern_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pattern (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, difficulty INT NOT NULL, created_at DATETIME NOT NULL, pdf_filename VARCHAR(255) NOT NULL, INDEX IDX_A3BCFC8E12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pattern_yarn (pattern_id INT NOT NULL, yarn_id INT NOT NULL, INDEX IDX_1F48F99FF734A20F (pattern_id), INDEX IDX_1F48F99FE6DFB9C4 (yarn_id), PRIMARY KEY(pattern_id, yarn_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE yarn (id INT AUTO_INCREMENT NOT NULL, brand_id INT NOT NULL, name VARCHAR(255) NOT NULL, weight VARCHAR(255) NOT NULL, INDEX IDX_C965C63644F5D008 (brand_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FF734A20F FOREIGN KEY (pattern_id) REFERENCES pattern (id)');
        $this->addSql('ALTER TABLE pattern ADD CONSTRAINT FK_A3BCFC8E12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE pattern_yarn ADD CONSTRAINT FK_1F48F99FF734A20F FOREIGN KEY (pattern_id) REFERENCES pattern (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pattern_yarn ADD CONSTRAINT FK_1F48F99FE6DFB9C4 FOREIGN KEY (yarn_id) REFERENCES yarn (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE yarn ADD CONSTRAINT FK_C965C63644F5D008 FOREIGN KEY (brand_id) REFERENCES brand (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE yarn DROP FOREIGN KEY FK_C965C63644F5D008');
        $this->addSql('ALTER TABLE pattern DROP FOREIGN KEY FK_A3BCFC8E12469DE2');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045FF734A20F');
        $this->addSql('ALTER TABLE pattern_yarn DROP FOREIGN KEY FK_1F48F99FF734A20F');
        $this->addSql('ALTER TABLE pattern_yarn DROP FOREIGN KEY FK_1F48F99FE6DFB9C4');
        $this->addSql('DROP TABLE brand');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE pattern');
        $this->addSql('DROP TABLE pattern_yarn');
        $this->addSql('DROP TABLE yarn');
    }
}

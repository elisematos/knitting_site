<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201128163643 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pattern_yarn (pattern_id INT NOT NULL, yarn_id INT NOT NULL, INDEX IDX_1F48F99FF734A20F (pattern_id), INDEX IDX_1F48F99FE6DFB9C4 (yarn_id), PRIMARY KEY(pattern_id, yarn_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pattern_yarn ADD CONSTRAINT FK_1F48F99FF734A20F FOREIGN KEY (pattern_id) REFERENCES pattern (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pattern_yarn ADD CONSTRAINT FK_1F48F99FE6DFB9C4 FOREIGN KEY (yarn_id) REFERENCES yarn (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE yarn_pattern');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE yarn_pattern (yarn_id INT NOT NULL, pattern_id INT NOT NULL, INDEX IDX_87A7F9FBF734A20F (pattern_id), INDEX IDX_87A7F9FBE6DFB9C4 (yarn_id), PRIMARY KEY(yarn_id, pattern_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE yarn_pattern ADD CONSTRAINT FK_87A7F9FBE6DFB9C4 FOREIGN KEY (yarn_id) REFERENCES yarn (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE yarn_pattern ADD CONSTRAINT FK_87A7F9FBF734A20F FOREIGN KEY (pattern_id) REFERENCES pattern (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE pattern_yarn');
    }
}

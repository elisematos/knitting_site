<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201202151827 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F77399416');
        $this->addSql('DROP INDEX IDX_C53D045F77399416 ON image');
        $this->addSql('ALTER TABLE image CHANGE patterns_id pattern_id INT NOT NULL');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045FF734A20F FOREIGN KEY (pattern_id) REFERENCES pattern (id)');
        $this->addSql('CREATE INDEX IDX_C53D045FF734A20F ON image (pattern_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045FF734A20F');
        $this->addSql('DROP INDEX IDX_C53D045FF734A20F ON image');
        $this->addSql('ALTER TABLE image CHANGE pattern_id patterns_id INT NOT NULL');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F77399416 FOREIGN KEY (patterns_id) REFERENCES pattern (id)');
        $this->addSql('CREATE INDEX IDX_C53D045F77399416 ON image (patterns_id)');
    }
}

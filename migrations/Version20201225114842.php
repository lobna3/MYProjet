<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201225114842 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pin ADD image_id INT DEFAULT NULL, DROP image_name');
        $this->addSql('ALTER TABLE pin ADD CONSTRAINT FK_B5852DF33DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B5852DF33DA5256D ON pin (image_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE pin DROP FOREIGN KEY FK_B5852DF33DA5256D');
        $this->addSql('DROP INDEX UNIQ_B5852DF33DA5256D ON pin');
        $this->addSql('ALTER TABLE pin ADD image_name VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, DROP image_id');
    }
}

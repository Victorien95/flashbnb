<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200820160728 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ad CHANGE price price INT NOT NULL, CHANGE ad_cover_image ad_cover_image VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE booking CHANGE promo_code_id promo_code_id INT DEFAULT NULL, CHANGE amount amount INT NOT NULL, CHANGE ttc_amount ttc_amount INT NOT NULL');
        $this->addSql('ALTER TABLE image CHANGE url url VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE promo_code CHANGE user_id user_id INT DEFAULT NULL, CHANGE expired_at expired_at DATETIME DEFAULT NULL, CHANGE max_number max_number INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE picture picture VARCHAR(255) DEFAULT NULL, CHANGE avatarname avatarname VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ad CHANGE ad_cover_image ad_cover_image VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE price price DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE booking CHANGE promo_code_id promo_code_id INT DEFAULT NULL, CHANGE amount amount DOUBLE PRECISION NOT NULL, CHANGE ttc_amount ttc_amount DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE image CHANGE url url VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE promo_code CHANGE user_id user_id INT DEFAULT NULL, CHANGE expired_at expired_at DATETIME DEFAULT \'NULL\', CHANGE max_number max_number INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE avatarname avatarname VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE picture picture VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}

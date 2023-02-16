<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230216212408 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bookings (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, bookable_id INT NOT NULL, confirmation VARCHAR(255) NOT NULL, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL, booked_at DATETIME NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_7A853C35A76ED395 (user_id), INDEX IDX_7A853C35EC4F5B2F (bookable_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE unavailable_dates (id INT AUTO_INCREMENT NOT NULL, bookable_id INT NOT NULL, start_date DATETIME NOT NULL, end_date DATETIME NOT NULL, notes LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_17FA0BD0EC4F5B2F (bookable_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bookings ADD CONSTRAINT FK_7A853C35A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE bookings ADD CONSTRAINT FK_7A853C35EC4F5B2F FOREIGN KEY (bookable_id) REFERENCES bookable (id)');
        $this->addSql('ALTER TABLE unavailable_dates ADD CONSTRAINT FK_17FA0BD0EC4F5B2F FOREIGN KEY (bookable_id) REFERENCES bookable (id)');
        $this->addSql('ALTER TABLE bookable ADD category_id INT NOT NULL');
        $this->addSql('ALTER TABLE bookable ADD CONSTRAINT FK_A10B812412469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_A10B812412469DE2 ON bookable (category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bookable DROP FOREIGN KEY FK_A10B812412469DE2');
        $this->addSql('ALTER TABLE bookings DROP FOREIGN KEY FK_7A853C35A76ED395');
        $this->addSql('ALTER TABLE bookings DROP FOREIGN KEY FK_7A853C35EC4F5B2F');
        $this->addSql('ALTER TABLE unavailable_dates DROP FOREIGN KEY FK_17FA0BD0EC4F5B2F');
        $this->addSql('DROP TABLE bookings');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE unavailable_dates');
        $this->addSql('DROP INDEX IDX_A10B812412469DE2 ON bookable');
        $this->addSql('ALTER TABLE bookable DROP category_id');
    }
}

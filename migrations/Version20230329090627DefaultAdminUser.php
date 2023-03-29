<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230329090627DefaultAdminUser extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates the default Admin user';
    }

    public function up(Schema $schema): void
    {
        $password = '$2y$13$KwJAYrioYCQdYpieNnYeseef32BM0LXTVoIQBivkE7F7Hvz9EaSNi';
        $this->addSql("INSERT INTO user (email, password, roles, created_at, updated_at) VALUES ('admin@admin.com', '{$password}','[\"ROLE_ADMIN\"]',CURRENT_TIMESTAMP,CURRENT_TIMESTAMP)");
    }

    public function down(Schema $schema): void
    {
        $this->addSql("DELETE FROM user WHERE email = 'admin@admin.com'");
    }
}

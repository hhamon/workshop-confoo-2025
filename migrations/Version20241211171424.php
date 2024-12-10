<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241211171424 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add `health_specialist` table schema.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE health_specialist (id UUID NOT NULL, first_name TEXT NOT NULL, last_name TEXT NOT NULL, specialty TEXT NOT NULL, introduction TEXT DEFAULT NULL, biography TEXT DEFAULT NULL, profile_picture_url TEXT DEFAULT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE health_specialist');
    }
}

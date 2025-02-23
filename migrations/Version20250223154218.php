<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250223154218 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add `medical_appointment` cancellation fields.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE medical_appointment ADD cancelled_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE medical_appointment ADD cancellation_reason TEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE medical_appointment DROP cancelled_at');
        $this->addSql('ALTER TABLE medical_appointment DROP cancellation_reason');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250105000422 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add timestamp fields to `agenda_slot` table schema.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE agenda_slot ADD created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');
        $this->addSql('ALTER TABLE agenda_slot ADD updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL');

        $this->addSql('UPDATE agenda_slot SET created_at = NOW(), updated_at = NOW()');

        $this->addSql('ALTER TABLE agenda_slot ALTER created_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE agenda_slot ALTER created_at SET NOT NULL');
        $this->addSql('ALTER TABLE agenda_slot ALTER updated_at TYPE TIMESTAMP(0) WITHOUT TIME ZONE');
        $this->addSql('ALTER TABLE agenda_slot ALTER updated_at SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE agenda_slot DROP created_at');
        $this->addSql('ALTER TABLE agenda_slot DROP updated_at');
    }
}

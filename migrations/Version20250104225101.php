<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20250104225101 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add `medical_appointment` table schema.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE medical_appointment (id UUID NOT NULL, opening_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, closing_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, reference_number TEXT NOT NULL, first_name TEXT NOT NULL, folded_first_name TEXT NOT NULL, last_name TEXT NOT NULL, folded_last_name TEXT NOT NULL, email TEXT NOT NULL, phone TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, practitioner_id UUID NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6CC137F61121EA2C ON medical_appointment (practitioner_id)');
        $this->addSql('CREATE UNIQUE INDEX medical_appointment_reference_number_unique ON medical_appointment (reference_number)');
        $this->addSql('ALTER TABLE medical_appointment ADD CONSTRAINT FK_6CC137F61121EA2C FOREIGN KEY (practitioner_id) REFERENCES health_specialist (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE medical_appointment DROP CONSTRAINT FK_6CC137F61121EA2C');
        $this->addSql('DROP TABLE medical_appointment');
    }
}

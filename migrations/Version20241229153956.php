<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241229153956 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add `agenda` and `agenda_slot` table schemas';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE agenda (id UUID NOT NULL, is_published BOOLEAN NOT NULL, owner_id UUID NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2CEDC8777E3C61F9 ON agenda (owner_id)');
        $this->addSql('CREATE TABLE agenda_slot (id UUID NOT NULL, opening_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, closing_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, status TEXT NOT NULL, agenda_id UUID NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_912B8217EA67784A ON agenda_slot (agenda_id)');
        $this->addSql('CREATE UNIQUE INDEX agenda_slot_window_unique ON agenda_slot (agenda_id, opening_at, closing_at)');
        $this->addSql('ALTER TABLE agenda ADD CONSTRAINT FK_2CEDC8777E3C61F9 FOREIGN KEY (owner_id) REFERENCES health_specialist (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE agenda_slot ADD CONSTRAINT FK_912B8217EA67784A FOREIGN KEY (agenda_id) REFERENCES agenda (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE agenda DROP CONSTRAINT FK_2CEDC8777E3C61F9');
        $this->addSql('ALTER TABLE agenda_slot DROP CONSTRAINT FK_912B8217EA67784A');
        $this->addSql('DROP TABLE agenda');
        $this->addSql('DROP TABLE agenda_slot');
    }
}

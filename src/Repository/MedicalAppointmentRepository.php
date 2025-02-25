<?php

declare(strict_types=1);

namespace App\Repository;

interface MedicalAppointmentRepository
{
    public function exists(string $referenceNumber): bool;
}
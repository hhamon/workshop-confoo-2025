<?php

declare(strict_types=1);

namespace App\Service;

use App\Repository\MedicalAppointmentRepository;

class AppointmentReferenceGenerator
{
    public function __construct(
        private readonly MedicalAppointmentRepository $repository,
    ) {
    }

    public function generate(): string
    {
        do {
            $referenceNumber = $this->doGenerateReference();
        } while ($this->repository->exists($referenceNumber));

        return $referenceNumber;
    }

    private function doGenerateReference(): string
    {
        $chars = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';
        $chars = \str_shuffle($chars);

        $limit = \strlen($chars) - 1;
        $referenceNumber = '';
        for ($i = 1; $i <= 6; $i++) {
            $referenceNumber .= $chars[\random_int(0, $limit)];
        }

        return $referenceNumber;
    }
}

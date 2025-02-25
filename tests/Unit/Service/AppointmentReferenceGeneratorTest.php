<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Repository\MedicalAppointmentRepository;
use App\Service\AppointmentReferenceGenerator;
use PHPUnit\Framework\TestCase;

class AppointmentReferenceGeneratorTest extends TestCase
{
    public function testGenerateUniqueReference(): void
    {
        $repository = new class () implements MedicalAppointmentRepository {
            public function exists(string $referenceNumber): bool
            {
                return false;
            }
        };

        /*
        $repository = $this->createMock(MedicalAppointmentRepository::class);

        $repository
            ->expects($this->exactly(2))
            ->method('exists')
            ->willReturn(false);
        */

        $repository = $this->createMock(MedicalAppointmentRepository::class);

        $repository->expects($this->exactly(3))
            ->method('exists')
            ->willReturnOnConsecutiveCalls(true, false, false);

        $service = new AppointmentReferenceGenerator($repository);

        $reference1 = $service->generate();
        $reference2 = $service->generate();

        self::assertNotSame($reference1, $reference2);
    }
}
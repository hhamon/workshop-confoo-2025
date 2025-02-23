<?php

declare(strict_types=1);

namespace App\Tests\Integration\Service;

use App\Service\CancelAppointment;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

class CancelAppointmentTest extends KernelTestCase
{
    use Factories;
    use ResetDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        self::bootKernel();
    }

    public function testScheduledUpcomingAppointmentCanBeCancelled(): void
    {
        // Arrange: seed the database with a scheduled upcoming appointment

        // Act: cancel the appointment
        $service = $this->getCancelAppointmentService();

        // Assert: check the appointment is cancelled in the database
        // Assert: check the confirmation email has been sent
        // Assert: check one enqueued message dispatched in the message bus

    }

    private function getCancelAppointmentService(): CancelAppointment
    {
        return self::getContainer()->get(CancelAppointment::class);
    }
}

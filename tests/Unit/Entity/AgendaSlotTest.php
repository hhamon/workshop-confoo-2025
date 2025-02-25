<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Agenda;
use App\Entity\AgendaSlot;
use App\Entity\AgendaSlotStatus;
use App\Entity\HealthSpecialist;
use App\Entity\MedicalSpecialty;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class AgendaSlotTest extends TestCase
{
    private Agenda $agenda;

    protected function setUp(): void
    {
        parent::setUp();

        $this->agenda = $this->getDummyAgenda();
    }

    /**
     * @dataProvider provideInvalidDurations
     */
    public function testProvideInvalidDuration(string $duration): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid duration: ' . $duration);

        AgendaSlot::createOpenFromInterval($this->agenda, '2025-02-25 08:30', $duration);
    }

    public static function provideInvalidDurations(): iterable
    {
        yield ['5 minutes'];
        yield ['120 seconds'];
        yield ['FOO BAR'];
    }

    public function testCreateOpenFromInterval(): void
    {
        $slot = AgendaSlot::createOpenFromInterval($this->agenda, '2025-02-25 08:30', '30 minutes');

        self::assertSame(AgendaSlotStatus::OPEN, $slot->getStatus());
        self::assertEquals(new \DateTimeImmutable('2025-02-25 08:30'), $slot->getOpeningAt());
        self::assertEquals(new \DateTimeImmutable('2025-02-25 09:00'), $slot->getClosingAt());
        self::assertSame($this->agenda, $slot->getAgenda());
    }

    public function testCreateOpenAgendaSlot(): void
    {
        $slot = AgendaSlot::createOpen($this->agenda, '2025-02-25 08:30', '2025-02-25 09:00');

        self::assertSame(AgendaSlotStatus::OPEN, $slot->getStatus());
        self::assertEquals(new \DateTimeImmutable('2025-02-25 08:30'), $slot->getOpeningAt());
        self::assertEquals(new \DateTimeImmutable('2025-02-25 09:00'), $slot->getClosingAt());
        self::assertSame($this->agenda, $slot->getAgenda());
        //self::assertSame('2025-02-25 08:30', $slot->getOpeningAt()->format('Y-m-d H:i'));
    }

    private function getDummyAgenda(): Agenda
    {
        return new Agenda(
            new HealthSpecialist('John', 'Smith', MedicalSpecialty::CARDIOLOGIST)
        );
    }
}

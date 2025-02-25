<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use App\Entity\MedicalSpecialty;
use App\Factory\AgendaFactory;
use App\Factory\HealthSpecialistFactory;
use DAMA\DoctrineTestBundle\Doctrine\DBAL\StaticDriver;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Panther\PantherTestCase;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;

final class BookAppointmentControllerTest extends PantherTestCase
{
    use Factories;
    use HasBrowser;
    use ResetDatabase;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();

        StaticDriver::setKeepStaticConnections(false);
    }

    public static function tearDownAfterClass(): void
    {
        StaticDriver::setKeepStaticConnections(true);

        parent::tearDownAfterClass();
    }

    public function testBookADentistAppointment(): void
    {
        $specialist = HealthSpecialistFactory::createOne([
            'firstName' => 'Kevin',
            'lastName' => 'Costner',
            'specialty' => MedicalSpecialty::DENTIST,
        ]);

        AgendaFactory::new(['owner' => $specialist])
            ->published()
            ->withCalendar('today', 'tomorrow')
            ->create();

        $today = new DateTimeImmutable('today');

        $this->pantherBrowser()
            ->visit('/')
            ->click('Kevin Costner')
            ->click('18:00')
            ->fillField('medical_appointment_booking[firstName]', 'Lauren')
            ->fillField('medical_appointment_booking[lastName]', 'Montgomery')
            ->fillField('medical_appointment_booking[email]', 'l.montgomery@example.com')
            ->fillField('medical_appointment_booking[phone]', '+123456789')
            ->click('Book my appointment')
            ->assertSee(\sprintf('Your dentist appointment is scheduled on %s between 18:00 and 18:30.', $today->format('m/d/Y')))
            ->takeScreenshot('appointment-confirmation.png');
    }
}

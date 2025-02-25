<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Api;

use App\Factory\MedicalAppointmentFactory;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser\HttpOptions;
use Zenstruck\Browser\KernelBrowser;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;
use Zenstruck\Mailer\Test\Bridge\Zenstruck\Browser\MailerComponent;
use Zenstruck\Mailer\Test\InteractsWithMailer;

final class PostAppointmentCancellationControllerTest extends WebTestCase
{
    use Factories;
    use InteractsWithMailer;
    use ResetDatabase;

    use HasBrowser {
        browser as baseKernelBrowser;
    }

    public function testCancelScheduledUpcomingAppointment(): void
    {
        $appointment = MedicalAppointmentFactory::new()
            ->scheduled('E4N6ST')
            ->tomorrowAt('10:00')
            ->forPatient('John', 'Smith')
            ->create();

        self::ensureKernelShutdown();

        $this->browser()
            ->withProfiling()
            ->post(
                url: \sprintf('/api/appointments/%s/cancellation', $appointment->getId()),
                options: HttpOptions::json([
                    'referenceNumber' => 'E4N6ST',
                    'lastName' => 'SMITH',
                    'reason' => 'I booked another one earlier.',
                ]),
            )
            ->assertSuccessful()
            ->assertJson()
            ->assertJsonMatches('cancelledAt', $appointment->getCancelledAt()->format('c'))
            ->use(function (MailerComponent $component): void {
                $component->assertSentEmailCount(1);
            })
        ;

        // Assert: check the appointment is cancelled in the database
        MedicalAppointmentFactory::assert()->count(1, [
            'id' => $appointment->getId(),
            'cancelledAt' => $appointment->getCancelledAt(),
            'cancellationReason' => 'I booked another one earlier.',
        ]);
    }

    public function testAppointmentCancellationValidationFails(): void
    {
        $appointment = MedicalAppointmentFactory::new()
            ->scheduled('E4N6ST')
            ->tomorrowAt('10:00')
            ->forPatient('John', 'Smith')
            ->create();

        self::ensureKernelShutdown();

        $this->browser()
            ->withProfiling()
            ->post(
                url: \sprintf('/api/appointments/%s/cancellation', $appointment->getId()),
                options: HttpOptions::json([
                    'referenceNumber' => 'FOOBAR',
                    'lastName' => 'DOUGLAS',
                    'reason' => 'I booked another one earlier.',
                ]),
            )
            ->assertStatus(422)
            ->assertJson()
            ->assertJsonMatches('violations[0].title', 'Invalid appointment reference number.')
            ->assertJsonMatches('violations[1].title', 'Invalid appointment patient last name.')
            ->use(function (MailerComponent $component): void {
                $component->assertNoEmailSent();
            })
        ;
    }

    public function testCannotCancelPastAppointment(): void
    {
        $appointment = MedicalAppointmentFactory::new()
            ->scheduled('E4N6ST')
            ->yesterdayAt('10:00')
            ->forPatient('John', 'Smith')
            ->create();

        self::ensureKernelShutdown();

        $this->browser()
            ->withProfiling()
            ->post(
                url: \sprintf('/api/appointments/%s/cancellation', $appointment->getId()),
                options: HttpOptions::json([
                    'referenceNumber' => 'E4N6ST',
                    'lastName' => 'SMITH',
                    'reason' => 'I booked another one earlier.',
                ]),
            )
            ->assertStatus(422)
            ->assertJson()
            ->assertJsonMatches('violations[0].title', 'Appointment is no longer cancellable.')
            ->use(function (MailerComponent $component): void {
                $component->assertNoEmailSent();
            })
        ;
    }

    protected function browser(): KernelBrowser
    {
        return $this->baseKernelBrowser()
            ->interceptRedirects() // always intercept redirects
            ->throwExceptions() // always throw exceptions
            ;
    }
}

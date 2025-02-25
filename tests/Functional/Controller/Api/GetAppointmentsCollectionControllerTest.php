<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Api;

use App\Factory\MedicalAppointmentFactory;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Zenstruck\Browser\KernelBrowser;
use Zenstruck\Browser\Test\HasBrowser;
use Zenstruck\Foundry\Test\Factories;
use Zenstruck\Foundry\Test\ResetDatabase;
use Zenstruck\Mailer\Test\InteractsWithMailer;

final class GetAppointmentsCollectionControllerTest extends WebTestCase
{
    use Factories;
    use InteractsWithMailer;
    use ResetDatabase;

    use HasBrowser {
        browser as baseKernelBrowser;
    }

    public function testGetAppointmentsCollectionFilteredByDate(): void
    {
        $today = new DateTimeImmutable('today');
        $tomorrow = $today->modify('+1 day');
        $afterTomorrow = $tomorrow->modify('+1 day');

        MedicalAppointmentFactory::new()
            ->sequence([
                [
                    'openingAt' => $today->format('Y-m-d 10:00'),
                    'closingAt' => $today->format('Y-m-d 10:30'),
                ],
                [
                    'openingAt' => $today->format('Y-m-d 13:00'),
                    'closingAt' => $today->format('Y-m-d 13:30'),
                ],
                [
                    'openingAt' => $today->format('Y-m-d 13:30'),
                    'closingAt' => $today->format('Y-m-d 14:00'),
                ],
                [
                    'openingAt' => $tomorrow->format('Y-m-d 08:30'),
                    'closingAt' => $tomorrow->format('Y-m-d 09:00'),
                    'referenceNumber' => 'XRP9DZ',
                ],
                [
                    'openingAt' => $tomorrow->format('Y-m-d 11:00'),
                    'closingAt' => $tomorrow->format('Y-m-d 11:30'),
                    'referenceNumber' => 'W0ZR6X',
                ],
                [
                    'openingAt' => $afterTomorrow->format('Y-m-d 17:00'),
                    'closingAt' => $afterTomorrow->format('Y-m-d 17:30'),
                ],
            ])
            ->create();

        self::ensureKernelShutdown();

        $this->browser()
            ->withProfiling()
            ->get('/api/appointments', [
                'query' => [
                    'filter' => [
                        'date' => $tomorrow->format('Y-m-d'),
                    ],
                ],
            ])
            ->assertSuccessful()
            ->assertJson()
            ->assertJsonMatches('length([])', 2)
            ->assertJsonMatches('[0].referenceNumber', 'XRP9DZ')
            ->assertJsonMatches('[1].referenceNumber', 'W0ZR6X')
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

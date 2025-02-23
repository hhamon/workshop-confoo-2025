<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class PostAppointmentCancellationControllerTest extends WebTestCase
{
    public function testCannotCancelPastAppointment(): void
    {
        $client = static::createClient();
        $client->setServerParameter('HTTP_ACCEPT', 'application/json');

        $payload = \json_encode(
            value: [
                'referenceNumber' => 'E4N6ST',
                'lastName' => 'HAMON',
                'reason' => 'I booked another one earlier.',
            ],
            flags: \JSON_THROW_ON_ERROR,
        );

        $client->request(
            method: 'POST',
            uri: '/api/appointments/c44e8d7d-56f4-41ff-9c40-e12719875a0c/cancellation',
            content: $payload,
        );

        $this->assertResponseIsUnprocessable();

        //$this->assertResponseIsSuccessful();
        //$this->assertJsonContains(['@id' => '/']);
    }
}

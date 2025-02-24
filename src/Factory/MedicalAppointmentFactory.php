<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\MedicalAppointment;
use DateTime;
use DateTimeImmutable;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<MedicalAppointment>
 */
final class MedicalAppointmentFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return MedicalAppointment::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
    }

    public function cancelled(DateTimeImmutable|string|null $cancelledAt = null, ?string $cancellationReason = null): self
    {
        return $this->with(static function () use ($cancelledAt, $cancellationReason): array {
            if ($cancelledAt === null) {
                $cancelledAt = DateTimeImmutable::createFromMutable(
                    self::faker()->dateTimeBetween('-15 days', 'now'),
                );
            }

            return [
                'cancelledAt' => $cancelledAt,
                'cancellationReason' => $cancellationReason ?? self::faker()->realText(390),
            ];
        });
    }

    public function tomorrow(string $openingTime, string $closingTime): self
    {
        return $this->with(static function () use ($openingTime, $closingTime): array {
            $tomorrow = (new DateTimeImmutable('tomorrow'))->format('Y-m-d');

            return [
                'openingAt' => new DateTimeImmutable($tomorrow . ' ' . $openingTime),
                'closingAt' => new DateTimeImmutable($tomorrow . ' ' . $closingTime),
            ];
        });
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return function (): array {
            $openingAt = $this->generateOpeningDateTime();
            $closingAt = $openingAt->modify('+30 minutes');

            return [
                'referenceNumber' => self::faker()->unique()->regexify('/^[A-Z0-9]{6}/'),
                'practitioner' => HealthSpecialistFactory::randomOrCreate(),
                'firstName' => self::faker()->firstName(),
                'lastName' => self::faker()->lastName(),
                'email' => self::faker()->email(),
                'phone' => self::faker()->e164PhoneNumber(),
                'openingAt' => DateTimeImmutable::createFromMutable($openingAt),
                'closingAt' => DateTimeImmutable::createFromMutable($closingAt),
            ];
        };
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            ->beforeInstantiate(function(array $parameters, string $class, self $factory): array {
                $cancelledAt = $parameters['cancelledAt'] ?? null;

                if (\is_string($cancelledAt)) {
                    $parameters['cancelledAt'] = new DateTimeImmutable($cancelledAt);
                }

                return $parameters;
            })
            ->afterInstantiate(function (MedicalAppointment $medicalAppointment, array $parameters, self $factory): void {
                $cancelledAt = $parameters['cancelledAt'] ?? null;

                if ($cancelledAt instanceof DateTimeImmutable) {
                    $medicalAppointment->cancel($cancelledAt, $parameters['cancellationReason'] ?? null);
                }
            })
        ;
    }

    private function generateOpeningDateTime(): DateTime
    {
        $hour = self::faker()->numberBetween(8, 18);
        $minutes = self::faker()->randomElement([0, 30]);

        return self::faker()
            ->dateTimeBetween('-15 days', '+60 days')
            ->setTime($hour, $minutes);
    }
}

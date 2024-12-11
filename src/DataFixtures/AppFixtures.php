<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Agenda;
use App\Entity\AgendaSlot;
use App\Entity\AgendaSlotStatus;
use App\Entity\HealthSpecialist;
use App\Entity\MedicalSpecialty;
use DateInterval;
use DatePeriod;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator as Faker;

final class AppFixtures extends Fixture
{
    private static ?Faker $faker = null;

    private const array DAILY_AGENDA_SLOTS = [
        ['08:30', '09:00'],
        ['09:00', '09:30'],
        ['09:30', '10:00'],
        ['10:00', '10:30'],
        ['10:30', '11:00'],
        ['11:00', '11:30'],
        ['11:30', '12:00'],
        ['12:00', '12:30'],
        ['12:30', '13:00'],
        ['14:00', '14:30'],
        ['14:30', '15:00'],
        ['15:00', '15:30'],
        ['15:30', '16:00'],
        ['16:00', '16:30'],
        ['16:30', '17:00'],
        ['17:00', '17:30'],
        ['17:30', '18:00'],
        ['18:00', '18:30'],
        ['18:30', '19:00'],
        ['19:00', '19:30'],
    ];

    /**
     * TODO: to be extracted in a utility class and unit tested.
     */
    private static function generateCalendar(
        ObjectManager $manager,
        Agenda $agenda,
        string $firstDay,
        string $lastDay,
        bool $monday = true,
        bool $tuesday = true,
        bool $wednesday = true,
        bool $thursday = true,
        bool $friday = true,
        bool $saturday = true,
        bool $sunday = true,
    ): void {
        $dates = self::getDatesBetween($firstDay, $lastDay, $monday, $tuesday, $wednesday, $thursday, $friday, $saturday, $sunday);

        foreach ($dates as $date) {
            foreach (self::DAILY_AGENDA_SLOTS as $slot) {
                $manager->persist(
                    AgendaSlot::create(
                        agenda: $agenda,
                        status: self::faker()->randomElement(AgendaSlotStatus::cases()),
                        openingAt: $date->format('Y-m-d ') . $slot[0],
                        closingAt: $date->format('Y-m-d ') . $slot[1],
                    ),
                );
            }
        }
    }

    /**
     * TODO: to be extracted in a utility class and unit tested.
     *
     * @return DateTimeImmutable[]
     */
    private static function getDatesBetween(
        string $firstDay,
        string $lastDay,
        bool $monday = true,
        bool $tuesday = true,
        bool $wednesday = true,
        bool $thursday = true,
        bool $friday = true,
        bool $saturday = true,
        bool $sunday = true,
    ): array {
        $interval = new DateInterval('P1D');

        $period = new DatePeriod(
            new DateTimeImmutable($firstDay),
            $interval,
            (new DateTimeImmutable($lastDay))->add($interval), // Add +1 day to include the last day
        );

        $dates = [];
        foreach ($period as $date) {
            $keep = match ($date->format('N')) {
                '1' => $monday,
                '2' => $tuesday,
                '3' => $wednesday,
                '4' => $thursday,
                '5' => $friday,
                '6' => $saturday,
                '7' => $sunday,
                default => false,
            };

            if (!$keep) {
                continue;
            }

            $dates[] =  $date;
        }

        return $dates;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = self::faker();

        $specialist1 = new HealthSpecialist('Linda', 'Helmann', MedicalSpecialty::GYNECOLOGIST);
        $specialist1->setIntroduction($faker->text(maxNbChars: 164));
        $specialist1->setBiography($faker->paragraphs($faker->numberBetween(2, 6), asText: true));
        $specialist1->setProfilePictureUrl('https://images.unsplash.com/photo-1494790108377-be9c29b29330?fit=facearea&facepad=2&w=256&h=256&q=80');

        $specialist2 = new HealthSpecialist('James', 'Clark', MedicalSpecialty::OPHTHALMOLOGIST);
        $specialist2->setIntroduction($faker->text(maxNbChars: 164));
        $specialist2->setBiography($faker->paragraphs($faker->numberBetween(2, 6), asText: true));
        $specialist2->setProfilePictureUrl('https://images.unsplash.com/photo-1519244703995-f4e0f30006d5?fit=facearea&facepad=2&w=256&h=256&q=80');

        $specialist3 = new HealthSpecialist('Vincent', 'Dries', MedicalSpecialty::GYNECOLOGIST);
        $specialist3->setIntroduction($faker->text(maxNbChars: 164));
        $specialist3->setBiography($faker->paragraphs($faker->numberBetween(2, 6), asText: true));
        $specialist3->setProfilePictureUrl('https://images.unsplash.com/photo-1517841905240-472988babdf9');

        $specialist4 = new HealthSpecialist('Cassidy', 'McBeal', MedicalSpecialty::DERMATOLOGIST);
        $specialist4->setIntroduction($faker->text(maxNbChars: 164));
        $specialist4->setBiography($faker->paragraphs($faker->numberBetween(2, 6), asText: true));
        $specialist4->setProfilePictureUrl('https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?fit=facearea&facepad=2&w=256&h=256&q=80');

        $specialist5 = new HealthSpecialist('Courtney', 'Henry', MedicalSpecialty::ANESTHETIST);
        $specialist5->setIntroduction($faker->text(maxNbChars: 164));
        $specialist5->setBiography($faker->paragraphs($faker->numberBetween(2, 6), asText: true));
        $specialist5->setProfilePictureUrl('https://images.unsplash.com/photo-1438761681033-6461ffad8d80?fit=facearea&facepad=2&w=256&h=256&q=80');

        $specialist6 = new HealthSpecialist('Tom', 'Cook', MedicalSpecialty::DENTIST);
        $specialist6->setIntroduction($faker->text(maxNbChars: 164));
        $specialist6->setBiography($faker->paragraphs($faker->numberBetween(2, 6), asText: true));
        $specialist6->setProfilePictureUrl('https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?fit=facearea&facepad=2&w=256&h=256&q=80');

        $manager->persist($specialist1);
        $manager->persist($specialist2);
        $manager->persist($specialist3);
        $manager->persist($specialist4);
        $manager->persist($specialist5);
        $manager->persist($specialist6);

        $agenda1 = new Agenda($specialist1);
        $agenda2 = new Agenda($specialist2);
        $agenda3 = new Agenda($specialist3);
        $agenda4 = new Agenda($specialist4);
        $agenda5 = new Agenda($specialist5);
        $agenda6 = new Agenda($specialist6);

        $manager->persist($agenda1);
        $manager->persist($agenda2);
        $manager->persist($agenda3);
        $manager->persist($agenda4);
        $manager->persist($agenda5);
        $manager->persist($agenda6);

        $manager->flush();

        self::generateCalendar($manager, $agenda1, '+5 days', '+2 months', saturday: false, sunday: false);
        self::generateCalendar($manager, $agenda2, 'today', '+1 months', wednesday: false, thursday: false);
        self::generateCalendar($manager, $agenda3, '+4 months', '+4 months', monday: false, sunday: false);
        self::generateCalendar($manager, $agenda4, 'today', '+2 months', monday: false, saturday: false, sunday: false);
        self::generateCalendar($manager, $agenda5, 'today', '+1 months', tuesday: false, wednesday: false, sunday: false);
        self::generateCalendar($manager, $agenda6, 'today', '+4 months', friday: false, sunday: false);

        $manager->flush();

        $agenda1->publish();
        $agenda2->publish();
        $agenda3->unpublish();
        $agenda4->unpublish();
        $agenda5->publish();
        $agenda6->publish();

        $manager->flush();
    }

    private static function faker(): Faker
    {
        if (self::$faker === null) {
            self::$faker = Factory::create();
        }

        return self::$faker;
    }
}

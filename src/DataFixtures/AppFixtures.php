<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\MedicalSpecialty;
use App\Factory\AgendaFactory;
use App\Factory\HealthSpecialistFactory;
use App\Factory\MedicalAppointmentFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $specialist1 = HealthSpecialistFactory::createOne([
            'firstName' => 'Linda',
            'lastName' => 'Helmann',
            'specialty' => MedicalSpecialty::GYNECOLOGIST,
            'profilePictureUrl' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?fit=facearea&facepad=2&w=256&h=256&q=80',
        ]);

        $specialist2 = HealthSpecialistFactory::createOne([
            'firstName' => 'James',
            'lastName' => 'Clark',
            'specialty' => MedicalSpecialty::OPHTHALMOLOGIST,
            'profilePictureUrl' => 'https://images.unsplash.com/photo-1519244703995-f4e0f30006d5?fit=facearea&facepad=2&w=256&h=256&q=80',
        ]);

        $specialist3 = HealthSpecialistFactory::createOne([
            'firstName' => 'Vincent',
            'lastName' => 'Dries',
            'specialty' => MedicalSpecialty::GYNECOLOGIST,
            'profilePictureUrl' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?fit=facearea&facepad=2&w=256&h=256&q=80',
        ]);

        $specialist4 = HealthSpecialistFactory::createOne([
            'firstName' => 'Cassidy',
            'lastName' => 'McBeal',
            'specialty' => MedicalSpecialty::DERMATOLOGIST,
            'profilePictureUrl' => 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?fit=facearea&facepad=2&w=256&h=256&q=80',
        ]);

        $specialist5 = HealthSpecialistFactory::createOne([
            'firstName' => 'Courtney',
            'lastName' => 'Henry',
            'specialty' => MedicalSpecialty::ANESTHETIST,
            'profilePictureUrl' => 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?fit=facearea&facepad=2&w=256&h=256&q=80',
        ]);

        $specialist6 = HealthSpecialistFactory::createOne([
            'firstName' => 'Tom',
            'lastName' => 'Cook',
            'specialty' => MedicalSpecialty::DENTIST,
            'profilePictureUrl' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?fit=facearea&facepad=2&w=256&h=256&q=80',
        ]);

        AgendaFactory::new(['owner' => $specialist1])
            ->published()
            ->withCalendar('-2 days', '+65 days', saturday: false, sunday: false)
            ->create();

        AgendaFactory::new(['owner' => $specialist2])
            ->published()
            ->withCalendar('-2 days', '+10 days', wednesday: false, thursday: false)
            ->create();

        AgendaFactory::new(['owner' => $specialist3])->unpublished()->create();
        AgendaFactory::new(['owner' => $specialist4])->unpublished()->create();
        AgendaFactory::new(['owner' => $specialist5])->published()->create();
        AgendaFactory::new(['owner' => $specialist6])->published()->create();

        MedicalAppointmentFactory::new()
            ->cancelled()
            ->many(3)
            ->create();

        MedicalAppointmentFactory::createMany(10);
    }
}

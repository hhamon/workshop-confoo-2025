<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\HealthSpecialist;
use App\Entity\MedicalSpecialty;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

final class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $specialist1 = new HealthSpecialist('Linda', 'Helmann', MedicalSpecialty::GYNECOLOGIST);
        $specialist1->setIntroduction($faker->text(maxNbChars: 164));
        $specialist1->setBiography($faker->realTextBetween(maxNbChars: 2300));
        $specialist1->setProfilePictureUrl('https://images.unsplash.com/photo-1494790108377-be9c29b29330?fit=facearea&facepad=2&w=256&h=256&q=80');

        $specialist2 = new HealthSpecialist('James', 'Clark', MedicalSpecialty::OPHTHALMOLOGIST);
        $specialist2->setIntroduction($faker->text(maxNbChars: 164));
        $specialist2->setBiography($faker->realTextBetween(maxNbChars: 2300));
        $specialist2->setProfilePictureUrl('https://images.unsplash.com/photo-1519244703995-f4e0f30006d5?fit=facearea&facepad=2&w=256&h=256&q=80');

        $specialist3 = new HealthSpecialist('Vincent', 'Dries', MedicalSpecialty::GYNECOLOGIST);
        $specialist3->setIntroduction($faker->text(maxNbChars: 164));
        $specialist3->setBiography($faker->realTextBetween(maxNbChars: 2300));
        $specialist3->setProfilePictureUrl('https://images.unsplash.com/photo-1517841905240-472988babdf9');

        $specialist4 = new HealthSpecialist('Cassidy', 'McBeal', MedicalSpecialty::DERMATOLOGIST);
        $specialist4->setIntroduction($faker->text(maxNbChars: 164));
        $specialist4->setBiography($faker->realTextBetween(maxNbChars: 2300));
        $specialist4->setProfilePictureUrl('https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?fit=facearea&facepad=2&w=256&h=256&q=80');

        $specialist5 = new HealthSpecialist('Courtney', 'Henry', MedicalSpecialty::ANESTHETIST);
        $specialist5->setIntroduction($faker->text(maxNbChars: 164));
        $specialist5->setBiography($faker->realTextBetween(maxNbChars: 2300));
        $specialist5->setProfilePictureUrl('https://images.unsplash.com/photo-1438761681033-6461ffad8d80?fit=facearea&facepad=2&w=256&h=256&q=80');

        $specialist6 = new HealthSpecialist('Tom', 'Cook', MedicalSpecialty::DENTIST);
        $specialist6->setIntroduction($faker->text(maxNbChars: 164));
        $specialist6->setBiography($faker->realTextBetween(maxNbChars: 2300));
        $specialist6->setProfilePictureUrl('https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?fit=facearea&facepad=2&w=256&h=256&q=80');

        $manager->persist($specialist1);
        $manager->persist($specialist2);
        $manager->persist($specialist3);
        $manager->persist($specialist4);
        $manager->persist($specialist5);
        $manager->persist($specialist6);

        $manager->flush();
    }
}

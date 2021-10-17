<?php

namespace App\DataFixtures;

use App\Entity\Advisor;
use App\Entity\AdvisorLanguages;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create();

        for ($i = 0; $i < 10; $i++) {
            $advisor = new Advisor();
            $advisor->setName($faker->lastName);
            $advisor->setDescription($faker->realText(50));
            $advisor->setAvailability($faker->boolean(50));
            $advisor->setPricePerMinute($faker->randomFloat(2, 2));
            $manager->persist($advisor);

            $randomTotal = random_int(1, 4);
            for ($j = 0; $j < $randomTotal; $j++) {
                $advisorLanguages = new AdvisorLanguages();
                $advisorLanguages->setAdvisor($advisor);
                $advisorLanguages->setLanguageCode($faker->languageCode);
                $manager->persist($advisorLanguages);
            }
        }

        $manager->flush();
    }
}

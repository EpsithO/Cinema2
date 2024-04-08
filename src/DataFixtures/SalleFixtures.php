<?php

namespace App\DataFixtures;

use App\Entity\Salle;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Xylis\FakerCinema\Provider\Person
;

class SalleFixtures extends Fixture
{
    public const SALLE_REFERENCE = "salle-";
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $faker->addProvider(new Person($faker));
        for($i = 0; $i < 6; $i++){
            $salle = new Salle();
            $salle->setNomSalle($faker->actor);
            $salle->setNbPlace(mt_rand(50,100));
            $manager->persist($salle);
            $this->addReference(self::SALLE_REFERENCE.$i,$salle);
        }
        $manager->flush();
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\Film;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Xylis\FakerCinema\Provider\Movie

;

class FilmFixtures extends Fixture
{
        public const FILM_REFERENCE = "film-";
        public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();
        $faker->addProvider(new Movie($faker));
        for($i = 0; $i < 30; $i++){
            $film = new Film();
            $film->setTitre($faker->movie);
            $film->setDuree(mt_rand(90, 210));
            $manager->persist($film);
            $this->addReference(self::FILM_REFERENCE.$i,$film);
        }
        $manager->flush();
    }
}

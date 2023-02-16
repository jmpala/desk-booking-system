<?php

namespace App\DataFixtures;

use App\Factory\BookableFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Kinder Tisch
        BookableFactory::createOne([
            'pos_x' => 100,
            'pos_y' => 100,
        ]);

        BookableFactory::createOne([
            'pos_x' => 300,
            'pos_y' => 100,
        ]);

        BookableFactory::createOne([
            'pos_x' => 500,
            'pos_y' => 100,
        ]);

        BookableFactory::createOne([
            'pos_x' => 100,
            'pos_y' => 200,
        ]);

        BookableFactory::createOne([
            'pos_x' => 300,
            'pos_y' => 200,
        ]);

        // Wahre Entwickler Tisch
        BookableFactory::createOne([
            'pos_x' => 1000,
            'pos_y' => 100,
        ]);

        BookableFactory::createOne([
            'pos_x' => 1200,
            'pos_y' => 100,
        ]);

        BookableFactory::createOne([
            'pos_x' => 1400,
            'pos_y' => 100,
        ]);

        BookableFactory::createOne([
            'pos_x' => 1000,
            'pos_y' => 200,
        ]);

        BookableFactory::createOne([
            'pos_x' => 1200,
            'pos_y' => 200,
        ]);

        BookableFactory::createOne([
            'pos_x' => 1400,
            'pos_y' => 200,
        ]);

        // Verstellbarer Tisch
        BookableFactory::createOne([
            'pos_x' => 150,
            'pos_y' => 450,
        ]);

        BookableFactory::createOne([
            'pos_x' => 350,
            'pos_y' => 450,
        ]);

        $manager->flush();
    }
}

<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Factory\BookableFactory;
use App\Factory\CategoryFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // START REGISTER USERS
        UserFactory::createMany(10);

        UserFactory::createOne([
            'email' => 'user@user.com',
        ]);

        UserFactory::createOne([
            'email' => 'tl@user.com',
            'roles' => ['ROLE_TEAM_LEADER'],
        ]);

        UserFactory::createOne([
            'email' => 'admin@user.com',
            'roles' => ['ROLE_ADMIN'],
        ]);
        // END REGISTER USERS


        // START CATEGORY
        $entityDesk = CategoryFactory::createOne([
            'code' => 'desk',
            'name' => 'Normal Desk',
        ]);

        $entitySDesk = CategoryFactory::createOne([
            'code' => 'standingdesk',
            'name' => 'Standing Desk',
        ]);
        // END CATEGORY


        // START BOOKABLES
        $offsetX = -100;
        $offsetY = 0;

        // Kinder Tisch
        BookableFactory::createOne([
            'pos_x' => 100 + $offsetX,
            'pos_y' => 100 + $offsetY,
            'category' => $entityDesk,
        ]);

        BookableFactory::createOne([
            'pos_x' => 300 + $offsetX,
            'pos_y' => 100 + $offsetY,
            'category' => $entityDesk,
        ]);

        BookableFactory::createOne([
            'pos_x' => 500 + $offsetX,
            'pos_y' => 100 + $offsetY,
            'category' => $entityDesk,
        ]);

        BookableFactory::createOne([
            'pos_x' => 100 + $offsetX,
            'pos_y' => 200 + $offsetY,
            'category' => $entityDesk,
        ]);

        BookableFactory::createOne([
            'pos_x' => 300 + $offsetX,
            'pos_y' => 200 + $offsetY,
            'category' => $entityDesk,
        ]);

        // Wahre Entwickler Tisch
        BookableFactory::createOne([
            'pos_x' => 1000 + $offsetX,
            'pos_y' => 100 + $offsetY,
            'category' => $entityDesk,
        ]);

        BookableFactory::createOne([
            'pos_x' => 1200 + $offsetX,
            'pos_y' => 100 + $offsetY,
            'category' => $entityDesk,
        ]);

        BookableFactory::createOne([
            'pos_x' => 1400 + $offsetX,
            'pos_y' => 100 + $offsetY,
            'category' => $entityDesk,
        ]);

        BookableFactory::createOne([
            'pos_x' => 1000 + $offsetX,
            'pos_y' => 200 + $offsetY,
            'category' => $entityDesk,
        ]);

        BookableFactory::createOne([
            'pos_x' => 1200 + $offsetX,
            'pos_y' => 200 + $offsetY,
            'category' => $entityDesk,
        ]);

        BookableFactory::createOne([
            'pos_x' => 1400 + $offsetX,
            'pos_y' => 200 + $offsetY,
            'category' => $entityDesk,
        ]);

        // Verstellbarer Tisch
        BookableFactory::createOne([
            'pos_x' => 150 + $offsetX,
            'pos_y' => 450 + $offsetY,
            'category' => $entitySDesk,
        ]);

        BookableFactory::createOne([
            'pos_x' => 350 + $offsetX,
            'pos_y' => 450 + $offsetY,
            'category' => $entitySDesk,
        ]);
        // END BOOKABLES

        $manager->flush();
    }

}

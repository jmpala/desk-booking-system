<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Factory\BookableFactory;
use App\Factory\BookingsFactory;
use App\Factory\CategoryFactory;
use App\Factory\UnavailableDatesFactory;
use App\Factory\UserFactory;
use App\Repository\UnavailableDatesRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use DoctrineExtensions\Query\Mysql\Date;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // START REGISTER USERS
        UserFactory::createMany(10);

        $user1 = UserFactory::createOne([
            'email' => 'user01@user.com',
        ]);

        $user2 = UserFactory::createOne([
            'email' => 'user02@user.com',
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
        $desk1 = BookableFactory::createOne([
            'pos_x' => 100 + $offsetX,
            'pos_y' => 100 + $offsetY,
            'category' => $entityDesk,
            'code' => 'desk-01',
        ]);

        $desk2 = BookableFactory::createOne([
            'pos_x' => 300 + $offsetX,
            'pos_y' => 100 + $offsetY,
            'category' => $entityDesk,
            'code' => 'desk-02',
        ]);

        BookableFactory::createOne([
            'pos_x' => 500 + $offsetX,
            'pos_y' => 100 + $offsetY,
            'category' => $entityDesk,
            'code' => 'desk-03',
        ]);

        BookableFactory::createOne([
            'pos_x' => 100 + $offsetX,
            'pos_y' => 200 + $offsetY,
            'category' => $entityDesk,
            'code' => 'desk-04',
        ]);

        BookableFactory::createOne([
            'pos_x' => 300 + $offsetX,
            'pos_y' => 200 + $offsetY,
            'category' => $entityDesk,
            'code' => 'desk-05',
        ]);

        // Wahre Entwickler Tisch
        BookableFactory::createOne([
            'pos_x' => 1000 + $offsetX,
            'pos_y' => 100 + $offsetY,
            'category' => $entityDesk,
            'code' => 'desk-06',
        ]);

        BookableFactory::createOne([
            'pos_x' => 1200 + $offsetX,
            'pos_y' => 100 + $offsetY,
            'category' => $entityDesk,
            'code' => 'desk-07',
        ]);

        BookableFactory::createOne([
            'pos_x' => 1400 + $offsetX,
            'pos_y' => 100 + $offsetY,
            'category' => $entityDesk,
            'code' => 'desk-08',
        ]);

        BookableFactory::createOne([
            'pos_x' => 1000 + $offsetX,
            'pos_y' => 200 + $offsetY,
            'category' => $entityDesk,
            'code' => 'desk-09',
        ]);

        BookableFactory::createOne([
            'pos_x' => 1200 + $offsetX,
            'pos_y' => 200 + $offsetY,
            'category' => $entityDesk,
            'code' => 'desk-10',
        ]);

        BookableFactory::createOne([
            'pos_x' => 1400 + $offsetX,
            'pos_y' => 200 + $offsetY,
            'category' => $entityDesk,
            'code' => 'desk-11',
        ]);

        // Verstellbarer Tisch
        $stand1 = BookableFactory::createOne([
            'pos_x' => 150 + $offsetX,
            'pos_y' => 450 + $offsetY,
            'category' => $entitySDesk,
            'code' => 'desk-12',
        ]);

        $stand2 = BookableFactory::createOne([
            'pos_x' => 350 + $offsetX,
            'pos_y' => 450 + $offsetY,
            'category' => $entitySDesk,
            'code' => 'desk-13',
        ]);
        // END BOOKABLES


        // START DISABLED BOOKABLES
        UnavailableDatesFactory::createOne([
            'bookable' => $stand1,
            'start_date' => (new \DateTime())->modify('+1 day'),
            'end_date' => (new \DateTime())->modify('+1 day'),
            'notes' => 'Needing maintenance',
        ]);

        UnavailableDatesFactory::createOne([
            'bookable' => $stand1,
            'start_date' => new \DateTime(),
            'end_date' => new \DateTime(),
            'notes' => 'Needing maintenance',
        ]);
        // END DISABLED BOOKABLES


        // START BOOKING
        BookingsFactory::createOne([
            'bookable' => $desk1,
            'user' => $user1,
        ]);

        BookingsFactory::createOne([
            'bookable' => $desk2,
            'user' => $user2,
        ]);
        // END BOOKING

        $manager->flush();
    }

}

<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Factory\BookableFactory;
use App\Factory\BookingsFactory;
use App\Factory\CategoryFactory;
use App\Factory\UnavailableDatesFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

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
            'roles' => ['ROLE_TEAMLEAD'],
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
            'pos_x' => 180,
            'pos_y' => 918,
            'category' => $entityDesk,
            'code' => 'desk-01',
        ]);

        $desk2 = BookableFactory::createOne([
            'pos_x' => 419,
            'pos_y' => 918,
            'category' => $entityDesk,
            'code' => 'desk-02',
        ]);

        $desk3 = BookableFactory::createOne([
            'pos_x' => 613,
            'pos_y' => 918,
            'category' => $entityDesk,
            'code' => 'desk-03',
        ]);

        $desk4 = BookableFactory::createOne([
            'pos_x' => 1243,
            'pos_y' => 918,
            'category' => $entityDesk,
            'code' => 'desk-04',
        ]);

        $desk5 = BookableFactory::createOne([
            'pos_x' => 1474,
            'pos_y' => 918,
            'category' => $entityDesk,
            'code' => 'desk-05',
        ]);

        // Wahre Entwickler Tisch
        $desk6 = BookableFactory::createOne([
            'pos_x' => 1673,
            'pos_y' => 918,
            'category' => $entityDesk,
            'code' => 'desk-06',
        ]);

        BookableFactory::createOne([
            'pos_x' => 180,
            'pos_y' => 640,
            'category' => $entityDesk,
            'code' => 'desk-07',
        ]);

        BookableFactory::createOne([
            'pos_x' => 419,
            'pos_y' => 640,
            'category' => $entityDesk,
            'code' => 'desk-08',
        ]);

        BookableFactory::createOne([
            'pos_x' => 1243,
            'pos_y' => 640,
            'category' => $entityDesk,
            'code' => 'desk-09',
        ]);

        BookableFactory::createOne([
            'pos_x' => 1474,
            'pos_y' => 640,
            'category' => $entityDesk,
            'code' => 'desk-10',
        ]);

        BookableFactory::createOne([
            'pos_x' => 1673,
            'pos_y' => 640,
            'category' => $entityDesk,
            'code' => 'desk-11',
        ]);

        // Verstellbarer Tisch
        $stand1 = BookableFactory::createOne([
            'pos_x' => 280,
            'pos_y' => 490,
            'category' => $entitySDesk,
            'code' => 'desk-12',
        ]);

        $stand2 = BookableFactory::createOne([
            'pos_x' => 490,
            'pos_y' => 462,
            'category' => $entitySDesk,
            'code' => 'desk-13',
        ]);
        // END BOOKABLES


        // START DISABLED BOOKABLES
        UnavailableDatesFactory::createOne([
            'bookable' => $desk3,
            'start_date' => (new \DateTime())->modify('-17 day'),
            'end_date' => (new \DateTime())->modify('-17 day'),
            'notes' => 'Broken table leg',
        ]);

        UnavailableDatesFactory::createOne([
            'bookable' => $desk3,
            'start_date' => (new \DateTime())->modify('-16 day'),
            'end_date' => (new \DateTime())->modify('-16 day'),
            'notes' => 'Broken table leg',
        ]);

        UnavailableDatesFactory::createOne([
            'bookable' => $desk3,
            'start_date' => (new \DateTime())->modify('-15 day'),
            'end_date' => (new \DateTime())->modify('-15 day'),
            'notes' => 'Broken table leg',
        ]);

        UnavailableDatesFactory::createOne([
            'bookable' => $desk3,
            'start_date' => (new \DateTime())->modify('-14 day'),
            'end_date' => (new \DateTime())->modify('-14 day'),
            'notes' => 'Broken table leg',
        ]);

        UnavailableDatesFactory::createOne([
            'bookable' => $desk3,
            'start_date' => (new \DateTime())->modify('-13 day'),
            'end_date' => (new \DateTime())->modify('-13 day'),
            'notes' => 'Broken table leg',
        ]);

        UnavailableDatesFactory::createOne([
            'bookable' => $desk3,
            'start_date' => (new \DateTime())->modify('-12 day'),
            'end_date' => (new \DateTime())->modify('-12 day'),
            'notes' => 'Broken table leg',
        ]);

        UnavailableDatesFactory::createOne([
            'bookable' => $desk3,
            'start_date' => (new \DateTime())->modify('-11 day'),
            'end_date' => (new \DateTime())->modify('-11 day'),
            'notes' => 'Broken table leg',
        ]);

        UnavailableDatesFactory::createOne([
            'bookable' => $desk3,
            'start_date' => (new \DateTime())->modify('-10 day'),
            'end_date' => (new \DateTime())->modify('-10 day'),
            'notes' => 'Broken table leg',
        ]);

        UnavailableDatesFactory::createOne([
            'bookable' => $desk3,
            'start_date' => (new \DateTime())->modify('-9 day'),
            'end_date' => (new \DateTime())->modify('-9 day'),
            'notes' => 'Broken table leg',
        ]);

        UnavailableDatesFactory::createOne([
            'bookable' => $desk3,
            'start_date' => (new \DateTime())->modify('-8 day'),
            'end_date' => (new \DateTime())->modify('-8 day'),
            'notes' => 'Broken table leg',
        ]);

        UnavailableDatesFactory::createOne([
            'bookable' => $desk3,
            'start_date' => (new \DateTime())->modify('-7 day'),
            'end_date' => (new \DateTime())->modify('-7 day'),
            'notes' => 'Broken table leg',
        ]);

        UnavailableDatesFactory::createOne([
            'bookable' => $desk3,
            'start_date' => (new \DateTime())->modify('-6 day'),
            'end_date' => (new \DateTime())->modify('-6 day'),
            'notes' => 'Broken table leg',
        ]);

        UnavailableDatesFactory::createOne([
            'bookable' => $desk3,
            'start_date' => (new \DateTime())->modify('-5 day'),
            'end_date' => (new \DateTime())->modify('-1 day'),
            'notes' => 'Broken table leg',
        ]);

        UnavailableDatesFactory::createOne([
            'bookable' => $desk4,
            'start_date' => (new \DateTime())->modify('-2 day'),
            'end_date' => (new \DateTime())->modify('+2 day'),
            'notes' => 'Waiting for parts',
        ]);

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
            'bookable' => $desk5,
            'user' => $user1,
            'start_date' => (new \DateTime())->modify('-1 day'),
            'end_date' => (new \DateTime())->modify('+1 day'),
        ]);

        BookingsFactory::createOne([
            'bookable' => $desk6,
            'user' => $user1,
            'start_date' => (new \DateTime())->modify('-5 day'),
            'end_date' => (new \DateTime())->modify('-1 day'),
        ]);

        BookingsFactory::createOne([
            'bookable' => $desk1,
            'user' => $user1,
        ]);

        BookingsFactory::createOne([
            'bookable' => $desk2,
            'user' => $user2,
        ]);

        BookingsFactory::createOne([
            'bookable' => $stand1,
            'user' => $user2,
            'start_date' => (new \DateTime())->modify('+5 day'),
            'end_date' => (new \DateTime())->modify('+6 day'),
        ]);

        BookingsFactory::createOne([
            'bookable' => $stand1,
            'user' => $user1,
            'start_date' => (new \DateTime())->modify('-14 day'),
            'end_date' => (new \DateTime())->modify('-14 day'),
        ]);

        BookingsFactory::createOne([
            'bookable' => $stand1,
            'user' => $user1,
            'start_date' => (new \DateTime())->modify('-13 day'),
            'end_date' => (new \DateTime())->modify('-13 day'),
        ]);

        BookingsFactory::createOne([
            'bookable' => $stand1,
            'user' => $user1,
            'start_date' => (new \DateTime())->modify('-12 day'),
            'end_date' => (new \DateTime())->modify('-12 day'),
        ]);

        BookingsFactory::createOne([
            'bookable' => $stand1,
            'user' => $user1,
            'start_date' => (new \DateTime())->modify('-11 day'),
            'end_date' => (new \DateTime())->modify('-11 day'),
        ]);

        BookingsFactory::createOne([
            'bookable' => $stand1,
            'user' => $user1,
            'start_date' => (new \DateTime())->modify('-10 day'),
            'end_date' => (new \DateTime())->modify('-10 day'),
        ]);

        BookingsFactory::createOne([
            'bookable' => $stand1,
            'user' => $user1,
            'start_date' => (new \DateTime())->modify('-9 day'),
            'end_date' => (new \DateTime())->modify('-9 day'),
        ]);

        BookingsFactory::createOne([
            'bookable' => $stand1,
            'user' => $user1,
            'start_date' => (new \DateTime())->modify('-8 day'),
            'end_date' => (new \DateTime())->modify('-8 day'),
        ]);

        BookingsFactory::createOne([
            'bookable' => $stand1,
            'user' => $user1,
            'start_date' => (new \DateTime())->modify('-7 day'),
            'end_date' => (new \DateTime())->modify('-7 day'),
        ]);

        BookingsFactory::createOne([
            'bookable' => $stand1,
            'user' => $user1,
            'start_date' => (new \DateTime())->modify('-6 day'),
            'end_date' => (new \DateTime())->modify('-6 day'),
        ]);

        BookingsFactory::createOne([
            'bookable' => $stand1,
            'user' => $user1,
            'start_date' => (new \DateTime())->modify('-5 day'),
            'end_date' => (new \DateTime())->modify('-5 day'),
        ]);
        // END BOOKING

        $manager->flush();
    }

}

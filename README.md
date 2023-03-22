# Booking System

## Info

This is a booking system webapplication, that allows the booking of "bookables" (desks, rooms, devices, etc.) for a specific time period.

## Technologies

- PHP 8.2
- Symfony 6.2
- Bootstrap 5.3
- Konva (for the Map)

## Requirements

- DDEV already installed
- nodejs and npm or yarn already installed

## Steps

1. initialize DDEV on root directory
2. create ".env.local" inside root dir and set the "DATABASE_URL" variable ("ddev status" for correct value)
3. run `ddev composer install`
4. run `ddev composer dump-autoload`
5. run `yarn install` or `npm install
6. run `ddev start
7. run 'yarn run dev-server` or `npm run dev-server` (to serve statics on dev mode -> autoreload)
8. run `ddev php bin/console doctrine:schema:create`
9. run `ddev php bin/console doctrine:migrations:migrate`
10. run `ddev php bin/console doctrine:fixtures:load`
11. open the browser and go to `http://desk-booking-system.ddev.site`

## Features

- Map that shouws the bookables status per date
- Map loads bookables dinamically from DB
- CRUD bookings by user
- crud bookings for other users by teamleader
- CRUD bookables status by admin
- CRUD user acount by admin

## TODO

- Account Pannel for users
- Email notifications (registration, forgoten password, booking, etc.)
- Add more bookable types (rooms, devices, parking)
- Map creator & editor
- REST OpenAPI
- VUE
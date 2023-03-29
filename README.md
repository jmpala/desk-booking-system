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
2. set php version to 8.2 on .ddev/config.yaml
3. create ".env.local" inside root dir and set the "DATABASE_URL" variable ("ddev status" for correct value)
4. correct the server_version on config/packages/doctrine.yaml (to match the database version)
5. run `ddev start`
6. run `ddev composer install`
7. run `ddev composer dump-autoload`
8. run `ddev yarn install` or `ddev npm install`
9. run 'yarn run dev-server` or `npm run dev-server` (run on local, no on docker)
10. run `ddev php bin/console doctrine:database:create`
11. run `ddev php bin/console doctrine:migrations:migrate`
12. run `ddev php bin/console doctrine:fixtures:load`
13. run `ddev status` and copy the url to the browser

## Features

- [x] Map that shouws the bookables status per date
- [x] Map loads bookables dinamically from DB
- [x] CRUD bookings by user
- [x] CRUD bookings for other users by teamleader
- [x] CRUD bookables status by admin
- [x] CRUD user acount by admin

## TODO

- [ ] Account Pannel for users
- [ ] Email notifications (registration, forgoten password, booking, etc.)
- [ ] Add more bookable types (rooms, devices, parking)
- [ ] Map creator & editor
- [ ] REST OpenAPI
- [ ] VUE
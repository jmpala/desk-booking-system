# Office Booking System

Office Booking System lets you organize your office's seat-plan and book bookables (desks, rooms, etc.) for a specific
time period.

---

## Installation

### Requirements

- [DDEV](https://ddev.readthedocs.io) already installed
- [nodejs, npm](https://nodejs.org) or [yarn](https://classic.yarnpkg.com/) already installed

### Steps

```bash
# 1. initialize DDEV on root directory

# 2. set php version to 8.2 on .ddev/config.yaml

# 3. create ".env.local" inside root dir and set the "DATABASE_URL" variable ("ddev status" for correct value)

# 4. correct the server_version on config/packages/doctrine.yaml (to match the database version)

# 5. run:
ddev start
ddev composer install
ddev composer dump-autoload
ddev yarn install # or 'ddev npm install'
yarn run dev-server # or 'npm run dev-server' (run on local, no on ddev docker)
ddev php bin/console doctrine:database:create
ddev php bin/console doctrine:migrations:migrate
ddev php bin/console doctrine:fixtures:load
ddev php bin/console app:create-user-admin
ddev status # and copy the url to the browser

```

---

## Techstack

- PHP 8.2
- Symfony 6.2
- Vue (for the map component)
- Bootstrap

---

## Features

- There are three roles: admin, teamleader and user
- Users can Create, Read, Update and Delete bookings
- Users can reset their password, change their email and delete their account
- Teamleaders and Admins can Create, Read, Update and Delete bookings for other users
- Admins can enable and disable bookables for a period of time
- Admins can create, modify and delete user accounts
- The System inform by email on password reset, account deletion and password change

---

## Roadmap

- [ ] Send Email notifications when a booking is created, updated or deleted
- [ ] Add unit tests
- [ ] Add an Office Creation module, where the admin can upload a map of the office and create the bookables
- [ ] implement [APi Platform](https://api-platform.com/)

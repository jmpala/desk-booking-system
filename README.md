# Steps to run application in DEV

## Requirements

- DDEV already installed
- nodejs and npm / yarn already installed

## Steps

1. init DDEV on this directory
2. create .env.local inside root dir and set the "DATABASE_URL" variable ("ddev status" for correct value)
3. run "ddev composer install"
4. run "ddev composer dump-autoload"
5. run "yarn install" or "npm install"
6. run "ddev start"
7. run "yarn run dev-server" or "npm run dev-server" (to serve statics on dev mode -> autoreload)
8. run "ddev php bin/console doctrine:schema:create"
9. run "ddev php bin/console doctrine:migrations:migrate"
10. run "ddev php bin/console doctrine:fixtures:load"

## Important

- After each new change, reload migrations and fixtures!!!

## Change log

### 2023-03-07

- Create New Booking page implemented
  - retrieves selected bookable from previous page
  - submit button disable if no date is selected
  - on submit call API to check is selected dates are available

- Overview Page implemented
  - displays the seatmap component

- Seatmap implemented
  - shows a map with bookables loaded from DB
  - handle its state upon date changes, retrieving data from DB
  - disables submit button if no bookables are selected or if selected bookable is not available

- login page implemented
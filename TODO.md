# TODO

## VIP Smn`s TODOs (10.02.2023)

1. [ ] Main screen should be more clear on what it is for UX/UI
2. [x] Map should represent the office plan as if (big room)
3. [x] Map should show which desk is selected
4. [x] It could be useful to see who booked the desk direct on the "Karte"
5. [x] Legend of desk states
6. [x] Table desk easy names
7. [ ] Table Date German format (no sence for hour)
8. [ ] Confirmation message not good enought (Reservation Number)
9. [ ] Filter delete bookings that are in the past
10. [ ] For Plannning filer should be on the table, it has nothing to do with map
11. [ ] map can have a list where users can be selected for the new booking
12. [x] user availability should be a flag on table (implemented on its own table)

---

## Map TODOs

- [ ] recreate and understand the example https://codesandbox.io/s/pixi-viewport-drag-and-drop-wzr1cr?file=/src/Scene.js
- [ ] base my app on this https://demo.deskradar.com/floor/59f9e011f9ffa7000fabd84e

---

## Links

- https://dbdiagram.io/home

---

- Webpack or Encore?
  - Encore + Yarn
  - CORS!!! in order encore to work
  - Retrieve generated assets with Twig
  - Crash course yarn
- Security + login

- Fixtures & Foundry
- Session

- implement security (https://symfonycasts.com/screencast/symfony-security/install) or Documentation?
- Vue and Map component

---

## Desk statuses

- Available:
  - there is no booking on "bookings" table, neither on "unavailable_dates" table
- Unavailable:
  - there is a date on "unavailable_dates" table
- Booked:
  - there is a booking on "bookings" table
- Booked by me:
  - there is a booking on "bookings" table and the user is the owner of the booking

// are there any bookings made?
SELECT * FROM b
WHERE :date BETWEEN b.start AND b.end
LJ bk

// are there any unavailable dates for bookables?
SELECT * FROM u
WHERE :date BETWEEN u.start AND u.end
LJ bk
# TODO

## VIP Smn`s TODOs (10.02.2023)

31. [x] Main screen should be more clear on what it is for UX/UI
2. [x] Map should represent the office plan as if (big room)
3. [x] Map should show which desk is selected
4. [x] It could be useful to see who booked the desk direct on the "Karte"
5. [x] Legend of desk states
6. [x] Table desk easy names
7. [ ] Table Date German format (no sence for hour) <mark>**Depends on the machine**</mark>
8. [x] Confirmation message not good enought (Reservation Number)
9. [x] Filter delete bookings that are in the past
10. [X] For Plannning filer should be on the table, it has nothing to do with map
11. [ ] map can have a list where users can be selected for the new booking <mark>**need VUE**</mark>
12. [x] user availability should be a flag on table (implemented on its own table)

- What if 2 two bookings are made on the same time for the same resource?

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


## Ideas

- user slug instead of ids to search for users

### Overview Page

- Choose which date to start from. When compliting a new booking, should the user be redirected to the date of the booking?
- correct the seatmap map displacement when entered for the first time to the page
- change the date format displayed on the seatmap bookables to dd/mm/yyyy
- spinloader till the seatmap is loaded
- make the edit button to redirect to the edit page from selected booking

### Creation Page

- After a booking is created, is it reasobable to redirect the user to the overview page?

### Delete Booking

- what if the booking is not ended and the user attempts to delete it? Should the user be able to delete it?


### BookingAPIController

- Define a way to sanitize the data received from the client
"use strict";


import '../../../sass/pages/main/main_newBooking.scss';


type isAvailableAPIData = {
  isAvailable: boolean,
  unavailableDates: {
    id: number,
    from: Date,
    to: Date,
    notes: string,
    bookableCode: string,
  }[],
  bookings: {
    id: number,
    from: Date,
    to: Date,
    bookableCode: string,
  }[]
}


const dateDisplayFormat = 'en-GB';


const bookingFrom: HTMLFormElement = document.getElementById("newBookingForm");
const availableBookables: HTMLElement = document.getElementById("allBookables");
const fromDateDOMElement: HTMLElement = document.getElementById("fromDate");
const toDateDOMElement: HTMLElement = document.getElementById("toDate");
const submitBtn: HTMLElement = document.getElementById("submitBtn");
const errorArea: HTMLElement = document.getElementById("errorArea");
const errorList: HTMLElement = document.getElementById("errorList");


fromDateDOMElement.addEventListener("change", limitMinimunToDate);
fromDateDOMElement.addEventListener("change", validateButtonSate);
toDateDOMElement.addEventListener("change", validateButtonSate);
submitBtn.addEventListener('click', checkAvailabilityBeforeSubmit);


/**
 * Limits the minimum date of "To Date" not to be before "From Date"
 */
function limitMinimunToDate(): void {
  const fromDate: Date = new Date(fromDateDOMElement.value);
  const toDate: Date = new Date(toDateDOMElement.value);
  if (fromDate > toDate) {
    toDateDOMElement.value = fromDateDOMElement.value;
  }
  toDateDOMElement.min = fromDateDOMElement.value;
}


/**
 * Submit button is only enabled if both dates are set
 */
function validateButtonSate(): void {
  if (fromDateDOMElement.value && toDateDOMElement.value) {
    clearErrorMessages();
    submitBtn.disabled = false;
  } else {
    submitBtn.disabled = true;
  }
}


/**
 * Checks if the selected dates are available before submitting the form
 */
async function checkAvailabilityBeforeSubmit(e: Event): void {
  e.preventDefault();
  const id = availableBookables.value;
  const fromDate: Date = new Date(fromDateDOMElement.value);
  const toDate: Date = new Date(toDateDOMElement.value);

  const isAvailable: isAvailableAPIData = await isBookableAvailabilityBySelectedDatesRESTCall(id, fromDate, toDate);

  if (isAvailable['isAvailable']) {
    return bookingFrom.submit();
  }

  showBookingsOrUnavailableDates(isAvailable);
}


/**
 * Calls the internal API to check if the selected dates are available and returns if any,
 * the unavailable dates and bookings for the selected bookable
 *
 * @param from
 * @param to
 * @returns {Promise<isAvailableAPIData>}
 */
async function isBookableAvailabilityBySelectedDatesRESTCall(id: number, from: Date, to: Date): isAvailableAPIData {
  const res: Response = await fetch(`/api/booking/${id}/availability`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify({
      from: from,
      to: to
    })
  });

  return await res.json();
}


/**
 * Displays unavailable dates and bookings to the user, if any
 * @param isAvailable
 */
function showBookingsOrUnavailableDates(isAvailable: isAvailableAPIData): void {
  errorArea.classList.remove('error-display');
  errorArea.classList.add('error-display__show');
  errorList.innerHTML = '';

  isAvailable.bookings.forEach(booking => {
    errorList.innerHTML += `<li>${booking.bookableCode} booked from ${sanitizeDisplayDate(booking.from)} to ${sanitizeDisplayDate(booking.to)}</li>`;
  });

  isAvailable.unavailableDates.forEach(unavailableDate => {
    errorList.innerHTML += `<li>${unavailableDate.bookableCode} unavailable from ${sanitizeDisplayDate(unavailableDate.from)} to ${sanitizeDisplayDate(unavailableDate.to)}: ${unavailableDate.notes}</li>`;
  });
}


/**
 * Clears the error messages setted by showBookingsOrUnavailableDates()
 */
function clearErrorMessages(): void {
  errorArea.classList.add('error-display');
  errorArea.classList.remove('error-display__show');
  errorList.innerHTML = '';
}


/**
 * Creates a date string in the format defined in dateDisplayFormat
 *
 * @param date
 * @returns {string}
 */
function sanitizeDisplayDate(date: string): string {
  return new Date(date).toLocaleDateString(dateDisplayFormat);
}
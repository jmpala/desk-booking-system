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


type period = {
  from: Date,
  to: Date,
}


const dateDisplayFormat = 'en-GB';


const bookingFrom: HTMLFormElement = document.getElementById("newBookingForm");
const availableBookables: HTMLElement = document.getElementById("allBookables");
const fromDateDOMElement: HTMLElement = document.getElementById("fromDate");
const toDateDOMElement: HTMLElement = document.getElementById("toDate");
const submitBtn: HTMLElement = document.getElementById("submitBtn");
const errorArea: HTMLElement = document.getElementById("errorArea");
const errorList: HTMLElement = document.getElementById("errorList");

let selectedUnavailablePeriods: period[] = [];


fromDateDOMElement.addEventListener("change", limitMinimunToDate);
bookingFrom.addEventListener("change", checkFormSate);
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
function checkFormSate(e: Event): void {

  if (e.target !== availableBookables
    && e.target !== fromDateDOMElement
    && e.target !== toDateDOMElement) {
    return;
  }

  e.stopPropagation();

  if (checkIfSelectedDatesAreValid()
    || e.target === availableBookables) {
    clearErrorMessages();
    clearInputErrors();
    selectedUnavailablePeriods = [];
    submitBtn.disabled = false;
  } else {
    showErrorsOnInputsIfAny();
    submitBtn.disabled = true;
  }
}


/**
 * Checks if the selected dates are available before submitting the form
 */
async function checkAvailabilityBeforeSubmit(e: Event): void {
  e.preventDefault();
  const id: number = availableBookables.value;
  const from: Date = new Date(fromDateDOMElement.value);
  const to: Date = new Date(toDateDOMElement.value);

  const isAvailable: isAvailableAPIData = await isBookableAvailabilityBySelectedDatesRESTCall(id, from, to);

  if (isAvailable['isAvailable']) {
    return bookingFrom.submit();
  }

  showBookingsOrUnavailableDates(isAvailable);
  setUnavailablePeriods(isAvailable);

  showErrorsOnInputsIfAny();
}


/**
 * Calls the internal API to check if the selected dates are available and returns if any,
 * the unavailable dates and bookings for the selected bookable
 *
 * @param id
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
 * Resets and keeps track of the selected bookable unavailable periods
 *
 * @param isAvailable
 */
function setUnavailablePeriods(isAvailable: isAvailableAPIData): void {
  selectedUnavailablePeriods = [];

  isAvailable.unavailableDates.forEach(unavailableDate => {
    selectedUnavailablePeriods.push({
      from: new Date(unavailableDate.from),
      to: new Date(unavailableDate.to),
    });
  });

  isAvailable.bookings.forEach(booking => {
    selectedUnavailablePeriods.push({
      from: new Date(booking.from),
      to: new Date(booking.to),
    });
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


/**
 * Checks if the selected dates are not empty and if they are not overlapping
 * with any unavailable periods returned by the API
 *
 * @returns {boolean}
 */
function checkIfSelectedDatesAreValid(): boolean {
  const isEmpty: boolean = fromDateDOMElement.value === '' || toDateDOMElement.value === '';
  if (isEmpty) {
    return false;
  }

  const selectedPeriod: period = {
    from: new Date(fromDateDOMElement.value),
    to: new Date(toDateDOMElement.value),
  };

  const isUnavailable: boolean = selectedUnavailablePeriods.reduce((acc, period) => {
    return acc || checkIfPeriodsOverlap(selectedPeriod, period);
  }, false);
  if (isUnavailable) {
    return false;
  }

  return true;
}


/**
 * Checks if two periods are overlapping
 *
 * @param period1
 * @param period2
 * @returns {boolean}
 */
function checkIfPeriodsOverlap(period1: period, period2: period): boolean {
  return period1.from <= period2.to && period1.to >= period2.from;
}


/**
 * Shows the users when the selected dates are overlaping with existing
 * bookings or unavalilable_dates
 */
function showErrorsOnInputsIfAny() {
  clearInputErrors();

  const selectedPeriod: period = {
    from: new Date(fromDateDOMElement.value),
    to: new Date(toDateDOMElement.value),
  };
  const isSelectedPeriodOverlapping: boolean = selectedUnavailablePeriods.reduce((acc, period) => {
    return acc || checkIfPeriodsOverlap(selectedPeriod, period);
  }, false);

  if (isSelectedPeriodOverlapping) {
    fromDateDOMElement.classList.add('is-invalid');
    toDateDOMElement.classList.add('is-invalid');
  }
}


/**
 * Clears the errors applied to the input-date elements
 */
function clearInputErrors() {
  fromDateDOMElement.classList.remove('is-invalid');
  toDateDOMElement.classList.remove('is-invalid');
}
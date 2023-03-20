"use strict";


import '../../../../sass/pages/main/main_editBooking.scss';
import { period, sanitizeDisplayDate, checkIfDatesAreValid, checkIfPeriodsOverlap } from '../../utils/dateUtils';
import { isAvailableAPIData, isAvailableAPIDataOptions, checkBookableAvailabilityBySelectedDatesRESTCall, setUnavailablePeriods } from '../../api/checkBookableAvailabilityBySelectedDatesRESTCall';


const bookingFrom: HTMLFormElement = document.getElementById("editBookingForm");
const availableBookables: HTMLElement = document.getElementById("allBookables");
const fromDateDOMElement: HTMLElement = document.getElementById("fromDate");
const toDateDOMElement: HTMLElement = document.getElementById("toDate");
const submitBtn: HTMLElement = document.getElementById("submitBtn");
const errorArea: HTMLElement = document.getElementById("errorArea");
const errorList: HTMLElement = document.getElementById("errorList");

const bookingId: HTMLInputElement = document.getElementById('bookingId');

let selectedUnavailablePeriods: period[] = [];


fromDateDOMElement.addEventListener("change", limitMinimunToDate);
bookingFrom.addEventListener("change", checkFormSate);
submitBtn.addEventListener('click', checkAvailabilityBeforeSubmit);


/**
 * Limits the minimum date of "To Date" not to be earlier than "From Date"
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

  const isSelectionValid: boolean = checkIfDatesAreValid(
    fromDateDOMElement.value,
    toDateDOMElement.value,
    selectedUnavailablePeriods);

  if (isSelectionValid
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

  const options: isAvailableAPIDataOptions = {
    ignoreSelectedBooking: true,
    ignoreSelectedBookingId: bookingId.value,
  }

  const isAvailable: isAvailableAPIData = await checkBookableAvailabilityBySelectedDatesRESTCall(id, from, to, options);

  if (isAvailable['isAvailable']) {
    return bookingFrom.submit();
  }

  showBookingsOrUnavailableDates(isAvailable);
  selectedUnavailablePeriods = setUnavailablePeriods(isAvailable);

  showErrorsOnInputsIfAny();
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
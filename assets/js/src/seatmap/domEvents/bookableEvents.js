"use strict";

import {Bookable} from "../app/model/bookables";
import {AppState} from "../app/AppState";
import {extractDateFromDateIsoString, getColorByState} from "../utils/utils";
import {bookingStates} from "../app/enums";


/**
 * Debug tool to show the information of a bookable in the console
 *
 * @param bookable
 */
export function showBookableDebugInformationOnClickEvent(bookable: Bookable): void {
    bookable.setEventListeners([{
        event: "click",
        callback: (event) => {
          event.cancelBubble = true;
          console.log(bookable);
        }
    }]);
}

/**
 * Select or deselect a bookable depending on the clicked bookable and if
 * the bookable is available or not, show an alert if the bookable is not
 *
 * @param bookable
 * @param app
 */
export function handleBookableSelectionOnClickEvent(bookable: Bookable, app: AppState): void {
  bookable.setEventListeners([{
    event: "click",
    callback: (event) => {
      event.cancelBubble = true;

      if (bookable.isDisabled || bookable.isBooked || bookable.isBookedByLoggedUser) {
        app.setSelectedBooking(null);
        return _showAlert(bookable);
      }

      _changeButton(bookable, 'Start new booking', getColorByState(bookingStates.AVAILABLE));
      app.setSelectedBooking(bookable);
    }
  }]);
}

/**
 * Show an alert with the information of the bookable
 *
 * @param bookable
 * @private
 */
function _showAlert(bookable: Bookable): void {
  if (bookable.isDisabled) {
    _changeButton(bookable, 'Can not be booked', getColorByState(bookingStates.UNAVAILABLE), true);
    return alert(`This <<bookable>> is disabled, from ${extractDateFromDateIsoString(bookable.disabledFromDate)} to ${extractDateFromDateIsoString(bookable.disabledToDate)}. The reason/s is/are: ${bookable.disabledNotes}, for more information please contact the administrator.`);
  }

  if (bookable.isBookedByLoggedUser) {
    _changeButton(bookable, 'Modify booking', getColorByState(bookingStates.BOOKEDBYUSER), false);
    return alert(`This <<bookable>> is already booked by you, from ${extractDateFromDateIsoString(bookable.bookingStartDate)} to ${extractDateFromDateIsoString(bookable.bookingEndDate)}.`);
  }

  if (bookable.isBooked) {
    _changeButton(bookable, 'Already booked', getColorByState(bookingStates.BOOKED), true);
    return alert(`This <<bookable>> is already booked, from ${extractDateFromDateIsoString(bookable.bookingStartDate)} to ${extractDateFromDateIsoString(bookable.bookingEndDate)} by ${bookable.userName}.`);
  }
}

function _changeButton(bookable: Bookable, message: string, color: string, disable: boolean): void {
  const submitbtn = document.querySelector('#konva-submit');
  submitbtn.href = "javascript:void(0)";

  if (bookable.isBookedByLoggedUser) {
    submitbtn.href = "/booking/" + bookable.bookingId;
  }
  else if (!bookable.isBooked && !bookable.isDisabled) {
    submitbtn.href = "/booking/new";
  }

  submitbtn.innerHTML = message;
  submitbtn.style.backgroundColor = color;
  submitbtn.style.color = "black";
  submitbtn.disabled = disable;
}

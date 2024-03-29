"use strict";

import {Bookable} from "../app/model/bookables";
import {AppState} from "../app/AppState";
import {extractDateFromDateIsoString, getColorByState} from "../utils/utils";
import {bookingStates} from "../app/enums";
import {config} from "../config";


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
 * @param blockSelection
 */
export function handleBookableSelectionOnClickEvent(bookable: Bookable, app: AppState): void {
  bookable.setEventListeners([{
    event: "click",
    callback: (event) => {
      event.cancelBubble = true;

      if (bookable.isBookedByLoggedUser) {
        app.setSelectedBooking(bookable);
        return _showAlert(bookable, app.isReadonly);
      } else if (bookable.isDisabled || bookable.isBooked) {
        app.setSelectedBooking(null);
        return _showAlert(bookable, app.isReadonly);
      }

      _changeButton(bookable, _createStatusLabel(bookable), getColorByState(bookingStates.AVAILABLE), false, app.isReadonly);
      app.setSelectedBooking(bookable);
    }
  }]);
}

/**
 * Show an alert with the information of the bookable
 *
 * @param bookable
 * @param isreadOnly
 * @private
 */
function _showAlert(bookable: Bookable, isreadOnly: boolean): void {
  if (bookable.isDisabled) {
    _changeButton(bookable, _createStatusLabel(bookable), getColorByState(bookingStates.UNAVAILABLE), true, isreadOnly);
    return;
  }

  if (bookable.isBookedByLoggedUser) {
    _changeButton(bookable, _createStatusLabel(bookable), getColorByState(bookingStates.BOOKEDBYUSER), false, isreadOnly);
    return;
  }

  if (bookable.isBooked) {
    _changeButton(bookable, _createStatusLabel(bookable), getColorByState(bookingStates.BOOKED), true, isreadOnly);
    return;
  }4
}

function _changeButton(bookable: Bookable, message: string, color: string, disable: boolean, isReadOnly: boolean): void {
  const submitbtn = document.querySelector('#konva-submit');
  const datePicker = document.getElementById('datePicker');

  submitbtn.href = "javascript:void(0)";

  if (bookable.isBookedByLoggedUser) {
    submitbtn.href = config.urls.editBooking + "/" + bookable.bookingId;
  }
  else if (!bookable.isBooked && !bookable.isDisabled) {
    submitbtn.href = config.urls.newBooking + "?id=" + bookable.bookableId;
    submitbtn.href += datePicker.value ? `&date=${datePicker.value}` : "";
  }

  if (isReadOnly) {
    submitbtn.href = "javascript:void(0)";
    submitbtn.innerHTML = "Watching Past Bookings";
    submitbtn.style.backgroundColor = getColorByState(bookingStates.UNAVAILABLE);
    submitbtn.style.color = "black";
    submitbtn.disabled = isReadOnly;
    return;
  }

  submitbtn.innerHTML = message;
  submitbtn.style.backgroundColor = color;
  submitbtn.style.color = "black";
  submitbtn.disabled = disable;
}

function _createStatusLabel(bookable: Bookable): string {
  if (bookable.isDisabled) {
    return `${bookable.bookableCode} disabled till ${extractDateFromDateIsoString(bookable.disabledToDate)}`;
  }

  if (bookable.isBookedByLoggedUser) {
    return `Edit ${bookable.bookableCode} booked by you`;
  }

  if (bookable.isBooked) {
    return `${bookable.bookableCode} booked till ${extractDateFromDateIsoString(bookable.bookingEndDate)}`;
  }

  return `Book ${bookable.bookableCode}`;
}
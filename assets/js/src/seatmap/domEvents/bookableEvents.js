"use strict";

import {Bookable} from "../app/model/bookables";
import {AppState} from "../app/AppState";
import {extractDateFromDateIsoString} from "../utils/utils";


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
    return alert(`This <<bookable>> is disabled, from ${extractDateFromDateIsoString(bookable.disabledFromDate)} to ${extractDateFromDateIsoString(bookable.disabledToDate)}. The reason/s is/are: ${bookable.disabledNotes}, for more information please contact the administrator.`);
  }

  if (bookable.isBookedByLoggedUser) {
    return alert(`This <<bookable>> is already booked by you, from ${extractDateFromDateIsoString(bookable.bookingStartDate)} to ${extractDateFromDateIsoString(bookable.bookingEndDate)}.`);
  }

  if (bookable.isBooked) {
    return alert(`This <<bookable>> is already booked, from ${extractDateFromDateIsoString(bookable.bookingStartDate)} to ${extractDateFromDateIsoString(bookable.bookingEndDate)} by ${bookable.userName}.`);
  }
}


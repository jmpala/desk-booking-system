"use strict";

import {Bookable} from "../app/model/bookables";
import {config} from "../config";
import {AppState} from "../app/AppState";
import {extractDateFromDateIsoString} from "../utils/utils";

export function showInformationModalOnClickEvent(bookable: Bookable): void {
    const modal = document.querySelector(`${config.app.ui.information_modal.dom_id}`);

    if (modal == null) {
        throw new Error(`DOM-Element with id="${config.app.ui.information_modal.dom_id}" not found.`);
    }

    bookable.setEventListeners([{
        event: "click",
        callback: (event) => {
          event.cancelBubble = true;
          console.log(bookable);
        }
    }]);
}

export function selectSelfOnTheAppOnClickEvent(bookable: Bookable, app: AppState): void {
bookable.setEventListeners([{
        event: "click",
        callback: (event) => {
          event.cancelBubble = true;

          if (bookable.isDisabled || bookable.isBooked || bookable.isBookedByLoggedUser) {
            return alertUserOnUnavailableBookings(bookable);
          }

          app.setSelectedBooking(bookable);
        }
    }]);
}

function alertUserOnUnavailableBookings(bookable: Bookable): void {
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


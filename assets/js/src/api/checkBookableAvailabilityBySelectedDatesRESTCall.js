"use strict";

import {period} from "../utils/bookingUtils";

/**
 * Calls the internal API to check if the selected dates are available and returns if any,
 * the unavailable dates and bookings for the selected bookable
 *
 * With @options we can control the following behavior:
 *
 * - ignoreSelectedBooking: boolean, ignoreSelectedBookingId: number
 * Used to ignore a specific booking-id in case case we are editing the booking.
 * For example: A booking start 14.01.xx20 end 16.01.xx20, with ignore we can extend the end date to 17.01.xx20
 * without checking the booking overlaping with itself
 *
 * @param bookableId
 * @param from
 * @param to
 * @param options?
 * @returns {Promise<isAvailableAPIData>}
 */
export async function checkBookableAvailabilityBySelectedDatesRESTCall(bookableId: number, from: Date, to: Date, options?: isAvailableAPIDataOptions): isAvailableAPIData {

  if (options == null) {
    options = {
      ignoreSelectedBooking: false,
      ignoreSelectedBookingId: 0
    }
  }

  const res: Response = await fetch(`/api/booking/${bookableId}/availability`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify({
      from: from,
      to: to,
      ignoreBooking: options.ignoreSelectedBooking,
      ignoreBookingId: options.ignoreSelectedBookingId,
    })
  });

  return await res.json();
}


/**
 * Resets and keeps track of the selected bookable unavailable periods
 *
 * @param isAvailable
 * @returns {period[]}
 */
export function setUnavailablePeriods(isAvailable: isAvailableAPIData): period[] {
  const selectedUnavailablePeriods: period[] = [];

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

  return selectedUnavailablePeriods;
}


export type isAvailableAPIData = {
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


export type isAvailableAPIDataOptions = {
  ignoreSelectedBooking: boolean,
  ignoreSelectedBookingId: number
}
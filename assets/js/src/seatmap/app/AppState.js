"use strict";

import {Bookable} from "./model/bookables";
import type {getSeatmapStausByDateResponse} from "./rest/getSeatmapStausByDate";

/**
 * The AppState is in charge of keeping track of the bookables state on the
 * seatmap component
 */
export class AppState {

  _selectedDate: Date;

  _selectedBooking: Bookable;

  _bookings: Array<Bookable> = [];

  AppState(date: Date) {
    this._selectedDate = date;
  }

  /**
   * Updates the state of the app by fetching the bookings for the given date
   *
   * @returns {Promise<void>}
   * @param getSeatmapStausByDateResponse
   */
  updateStateWithNewSeatmapstate(newSeatmapState: getSeatmapStausByDateResponse): void {
    this._bookings = newSeatmapState.bookables;
    this._selectedDate = newSeatmapState.date;
  }

  /**
   * Returns all the {@link _bookings}
   *
   * @returns {Array<Bookable>}
   */
  getBookings(): Array<Bookable> {
    return this._bookings;
  }

  /**
   * Sets the selected bookable
   *
   * @param {null|Bookable} booking - bookable to be selected or null to deselect
   */
  setSelectedBooking(booking: ?Bookable): void {
    if (this._selectedBooking) {
      this._selectedBooking.unselectBookable();
    }
    this._selectedBooking = booking;
    if (this._selectedBooking) {
      this._selectedBooking.selectBookable();
    }
  }
}

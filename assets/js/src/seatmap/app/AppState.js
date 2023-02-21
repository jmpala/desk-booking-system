"use strict";

import {Bookable} from "./model/bookables";
import {getSeatmapStausByDate} from "./rest/getSeatmapStausByDate";
import type {ReturnedGetSeatmapStausByDateType} from "./rest/getSeatmapStausByDate";

export class AppState {

  _selectedDate: Date;

  _selectedBooking: Bookable;

  _bookings: Array<Bookable> = [];

  AppState(date: Date) {
    this._selectedDate = date;
  }

  async updateStateByDate(date: Date): void {
    const res: ReturnedGetSeatmapStausByDateType = await getSeatmapStausByDate(date);
    this._bookings = res.bookables;
    this._selectedDate = date;
  }

  getBookings(): Array<Bookable> {
    return this._bookings;
  }

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

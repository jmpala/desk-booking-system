"use strict";

import {Bookable} from "../model/bookables";
import {Shape} from "konva/lib/Shape";
import {Rect} from "konva/lib/shapes/Rect";
import {getColorByState} from "../../utils/utils";
import {bookingStates} from "../enums";

export async function getSeatmapStausByDate(date: Date): ReturnedGetSeatmapStausByDateType {
  const res = await fetch(`/sketch/${date}`);
  const json = await res.json();
  return jsonToArrayOfBookables(json);
}

function jsonToArrayOfBookables(json: ReturnedGetSeatmapStausByDateRESTType): ReturnedGetSeatmapStausByDateType {
  json.bookables = json.bookables.map((b: BookablesType) => {

    const bookable = new Bookable();

    bookable.shape = new Rect({
      x: b.shapeX,
      y: b.shapeY,
      width: b.shapeWidth,
      height: b.shapeHeight,
      fill: getColorByState(bookingStates.AVAILABLE),
      stroke: "black",
      strokeWidth: 1,
    });
    bookable.bookableId = b.bookableId;
    bookable.bookableCode = b.bookableCode;
    bookable.bookableDescription = b.bookableDescription;
    bookable.bookableCategory = b.bookableCategory;
    bookable.userName = b.userName;
    bookable.isBookedByLoggedUser = b.isBookedByLoggedUser;

    bookable.isBooked = b.isBooked;
    if (bookable.isBooked) {
      bookable.bookingId = b.bookingId;
      bookable.bookingConfirmationCode = b.bookingConfirmationCode;
      bookable.bookingStartDate = new Date(b.bookingStartDate);
      bookable.bookingEndDate = new Date(b.bookingEndDate);
      bookable.bookingCreatedAt = b.bookingCreatedAt;
      bookable.shape.fill(getColorByState(bookingStates.BOOKED));

      if (bookable.isBookedByLoggedUser) {
        bookable.shape.fill(getColorByState(bookingStates.BOOKEDBYUSER));
      }
    }

    bookable.isDisabled = b.isDisabled;
    if (bookable.isDisabled) {
      bookable.disabledFromDate = new Date(b.disabledFromDate);
      bookable.disabledToDate = new Date(b.disabledToDate);
      bookable.disabledNotes = b.disabledNotes;
      bookable.shape.fill(getColorByState(bookingStates.UNAVAILABLE));
    }

    return bookable;
  });

  return json;
}

type ReturnedGetSeatmapStausByDateRESTType = {
  date: Date,
  bookables: Array<BookablesType>
}

export type ReturnedGetSeatmapStausByDateType = {
  date: Date,
  bookables: Array<Bookable>
}

type BookablesType = {
  shapeX: number;
  shapeY: number;
  shapeWidth: number;
  shapeHeight: number;
  shape: Shape;
  bookableId: number;
  bookableCode: string;
  bookableDescription: string;
  bookableCategory: string;
  userName: string;
  isBooked: boolean;
  isBookedByLoggedUser: boolean;
  bookingId: number;
  bookingConfirmationCode: string;
  bookingStartDate: Date;
  bookingEndDate: Date;
  bookingCreatedAt: Date;
  isDisabled: boolean;
  disabledFromDate: Date;
  disabledToDate: Date;
  disabledNotes: string;
};
"use strict";

import {BookableDesk} from "./bookables";
import {bookingStates} from "../enums";
import {Shape} from "konva/lib/Shape";
import {Rect} from "konva/lib/shapes/Rect";
import {getColorByState} from "../utils";

type BookableDeskType = {
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

export function createNewBookableDesk(x: BookableDeskType): BookableDesk {
  const desk = new BookableDesk();

  desk.shape = new Rect({
    x: x.shapeX,
    y: x.shapeY,
    width: x.shapeWidth,
    height: x.shapeHeight,
    fill: getColorByState(bookingStates.AVAILABLE),
  });
  desk.bookableId = x.bookableId;
  desk.bookableCode = x.bookableCode;
  desk.bookableDescription = x.bookableDescription;
  desk.bookableCategory = x.bookableCategory;
  desk.userName = x.userName;
  desk.isBookedByLoggedUser = x.isBookedByLoggedUser;

  desk.isBooked = x.isBooked;
  if (desk.isBooked) {
    desk.bookingId = x.bookingId;
    desk.bookingConfirmationCode = x.bookingConfirmationCode;
    desk.bookingStartDate = x.bookingStartDate;
    desk.bookingEndDate = x.bookingEndDate;
    desk.bookingCreatedAt = x.bookingCreatedAt;
    desk.shape.fill(getColorByState(bookingStates.BOOKED));

    if (desk.isBookedByLoggedUser) {
      desk.shape.fill(getColorByState(bookingStates.BOOKEDBYUSER));
    }
  }

  desk.isDisabled = x.isDisabled;
  if (desk.isDisabled) {
    desk.disabledFromDate = x.disabledFromDate;
    desk.disabledToDate = x.disabledToDate;
    desk.disabledNotes = x.disabledNotes;
    desk.shape.fill(getColorByState(bookingStates.UNAVAILABLE));
  }

  return desk;
}
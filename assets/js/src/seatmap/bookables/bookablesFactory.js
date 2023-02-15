"use strict";

import {BookableDesk} from "./bookables";
import {Rect} from "konva/lib/shapes/Rect";
import {bookingStates} from "../enums";
import {config} from "../config";

export function createNewBookableDesk({uuid, x , y, width, height, state}): Rect {
  const desk = new BookableDesk();
  desk.uuid = uuid;
  desk.state = state;
  desk.shape = new Rect({
    x, y,
    width, height,
    fill: getColorByState(state),
  });

  return desk.shape;
}

function getColorByState(state: bookingStates): string {
  switch (state) {
    case bookingStates.AVAILABLE:
      return config.app.bookables.state[bookingStates.AVAILABLE];
    case bookingStates.UNAVAILABLE:
      return config.app.bookables.state[bookingStates.UNAVAILABLE];
    case bookingStates.BOOKED:
      return config.app.bookables.state[bookingStates.BOOKED];
    case bookingStates.BOOKEDBYUSER:
      return config.app.bookables.state[bookingStates.BOOKEDBYUSER];
    default:
      throw new Error(`Booking state ${state} is not registered`);
  }
}
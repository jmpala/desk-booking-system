"use strict";

import {BookableDesk} from "./bookables";
import {Rect} from "konva/lib/shapes/Rect";
import {bookingStates} from "../enums";
import {config} from "../config";

type BookableDeskType = {
  uuid: string,
  x: number,
  y: number,
  width: number,
  height: number,
  state: bookingStates,
};

export function createNewBookableDesk(x: BookableDeskType): BookableDesk {
  const desk = new BookableDesk();
  desk.uuid = x.uuid;
  desk.state = x.state;
  desk.shape = new Rect({
    x: x.x, y: x.y,
    width: x.width, height: x.height,
    fill: getColorByState(x.state),
  });

  return desk;
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
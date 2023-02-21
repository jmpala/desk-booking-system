"use strict";

import {Group} from "konva/lib/Group";
import {Bookable} from "../../app/model/bookables";
import {Text} from "konva/lib/shapes/Text";
import {extractDateFromDateIsoString} from "../../utils/utils";


const padding: number = 10;
const fontSize: number = 12;
const fontFamily: string = 'Roboto, sans-serif';


export function createBookableInformationLabels(bookable: Bookable): Group {

  if (!bookable.isBooked && !bookable.isDisabled) {
    return createLabelsForAvailableBookable(bookable);
  } else if (bookable.isBookedByLoggedUser) {
    return createLabelsForBookedByLoggedUser(bookable);
  } else if (bookable.isBooked) {
    return createLabelsForBookedBookable(bookable);
  } else if (bookable.isDisabled) {
    return createLabelsForDisabledBookable(bookable);
  }
}

function createLabelsForAvailableBookable(bookable: Bookable): Group {
  const container = new Group();

  const deskNameLabel = new Text({
    x: bookable.shape.x() + padding,
    y: bookable.shape.y() + padding,
    width: bookable.shape.width(),
    text: 'Inventory: ' + bookable.bookableCode,
    fontSize,
    fontFamily,
    listening: false,
  });

  const availableLabel = new Text({
    x: bookable.shape.x() + padding,
    y: bookable.shape.y() + padding + fontSize,

    width: bookable.shape.width(),
    text: 'Available',
    fontSize,
    fontFamily,
    listening: false,
  });

  container.add(deskNameLabel);
  container.add(availableLabel);

  return container;
}

function createLabelsForBookedByLoggedUser(bookable: Bookable): Group {
  const container = new Group();

  const deskNameLabel = new Text({
    x: bookable.shape.x() + padding,
    y: bookable.shape.y() + padding,
    width: bookable.shape.width(),
    text: 'Inventory: ' + bookable.bookableCode,
    fontSize,
    fontFamily,
    listening: false,
  });

  const bookedByLabel = new Text({
    x: bookable.shape.x() + padding,
    y: bookable.shape.y() + padding + fontSize,
    width: bookable.shape.width(),
    text: 'Booked by: ' + bookable.userName,
    fontSize,
    fontFamily,
    listening: false,
  });

  const bookStartLabel = new Text({
    x: bookable.shape.x() + padding,
    y: bookable.shape.y() + padding + fontSize * 2,
    width: bookable.shape.width(),
    text: 'From: ' + extractDateFromDateIsoString(bookable.bookingStartDate),
    fontSize,
    fontFamily,
    listening: false,
  });

  const bookEndLabel = new Text({
    x: bookable.shape.x() + padding,
    y: bookable.shape.y() + padding + fontSize * 3,
    width: bookable.shape.width(),
    text: 'To: ' + extractDateFromDateIsoString(bookable.bookingEndDate),
    fontSize,
    fontFamily,
    listening: false,
  });

  container.add(deskNameLabel);
  container.add(bookedByLabel);
  container.add(bookStartLabel);
  container.add(bookEndLabel);

  return container;
}

function createLabelsForBookedBookable(bookable: Bookable): Group {
  const container = new Group();

  const deskNameLabel = new Text({
    x: bookable.shape.x() + padding,
    y: bookable.shape.y() + padding,
    width: bookable.shape.width(),
    text: 'Inventory: ' + bookable.bookableCode,
    fontSize,
    fontFamily,
    listening: false,
  });

  const bookedByLabel = new Text({
    x: bookable.shape.x() + padding,
    y: bookable.shape.y() + padding + fontSize,
    width: bookable.shape.width(),
    text: 'Booked by: ' + bookable.userName,
    fontSize,
    fontFamily,
    listening: false,
  });

  const bookStartLabel = new Text({
    x: bookable.shape.x() + padding,
    y: bookable.shape.y() + padding + fontSize * 2,
    width: bookable.shape.width(),
    text: 'From: ' + extractDateFromDateIsoString(bookable.bookingStartDate),
    fontSize,
    fontFamily,
    listening: false,
  });

  const bookEndLabel = new Text({
    x: bookable.shape.x() + padding,
    y: bookable.shape.y() + padding + fontSize * 3,
    width: bookable.shape.width(),
    text: 'To: ' + extractDateFromDateIsoString(bookable.bookingEndDate),
    fontSize,
    fontFamily,
    listening: false,
  });

  container.add(deskNameLabel);
  container.add(bookedByLabel);
  container.add(bookStartLabel);
  container.add(bookEndLabel);

  return container;
}

function createLabelsForDisabledBookable(bookable: Bookable): Group {
  const container = new Group();

  const deskNameLabel = new Text({
    x: bookable.shape.x() + padding,
    y: bookable.shape.y() + padding,
    width: bookable.shape.width(),
    text: 'Inventory: ' + bookable.bookableCode,
    fontSize,
    fontFamily,
    listening: false,
  });

  const disabledLabel = new Text({
    x: bookable.shape.x() + padding,
    y: bookable.shape.y() + padding + fontSize,
    width: bookable.shape.width(),
    text: 'Desk Disabled',
    fontSize,
    fontFamily,
    listening: false,
  });

  const disabledFromLabel = new Text({
    x: bookable.shape.x() + padding,
    y: bookable.shape.y() + padding + fontSize * 2,
    width: bookable.shape.width(),
    text: 'From: ' + extractDateFromDateIsoString(bookable.disabledFromDate),
    fontSize,
    fontFamily,
    listening: false,
  });

  const disabledToLabel = new Text({
    x: bookable.shape.x() + padding,
    y: bookable.shape.y() + padding + fontSize * 3,
    width: bookable.shape.width(),
    text: 'To: ' + extractDateFromDateIsoString(bookable.disabledToDate),
    fontSize,
    fontFamily,
    listening: false,
  });

  const disableNotesLabel = new Text({
    x: bookable.shape.x() + padding,
    y: bookable.shape.y() + padding + fontSize * 4,
    width: bookable.shape.width(),
    text: 'Notes: ' + bookable.disabledNotes,
    fontSize,
    fontFamily,
    listening: false,
  });

  container.add(deskNameLabel);
  container.add(disabledLabel);
  container.add(disabledFromLabel);
  container.add(disabledToLabel);
  container.add(disableNotesLabel);

  return container;
}
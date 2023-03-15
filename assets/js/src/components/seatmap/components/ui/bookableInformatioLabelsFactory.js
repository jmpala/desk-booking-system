"use strict";

import {Group} from "konva/lib/Group";
import {Bookable} from "../../app/model/bookables";
import {Text} from "konva/lib/shapes/Text";
import {extractDateFromDateIsoString} from "../../utils/utils";


const padding: number = 10;
const fontSize: number = 12;
const fontFamily: string = 'system-ui';


/**
 *  Creates the information ui-labels for the bookable object
 *
 * @param bookable
 * @returns {Group}
 */
export function createBookableInformationLabels(bookable: Bookable): Group {

  if (!bookable.isBooked && !bookable.isDisabled) {
    return _createLabelsForAvailableBookable(bookable);
  } else if (bookable.isBookedByLoggedUser) {
    return _createLabelsForBookedByLoggedUser(bookable);
  } else if (bookable.isBooked) {
    return _createLabelsForBookedBookable(bookable);
  } else if (bookable.isDisabled) {
    return _createLabelsForDisabledBookable(bookable);
  }
}

function _createLabelsForAvailableBookable(bookable: Bookable): Group {
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

function _createLabelsForBookedByLoggedUser(bookable: Bookable): Group {
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

  const confirmationLabel = new Text({
    x: bookable.shape.x() + padding,
    y: bookable.shape.y() + padding + fontSize,
    width: bookable.shape.width(),
    text: 'Confirmation: ' + bookable.bookingConfirmationCode,
    fontSize,
    fontFamily,
    listening: false,
  });

  const bookedByLabel = new Text({
    x: bookable.shape.x() + padding,
    y: bookable.shape.y() + padding + fontSize * 2,
    width: bookable.shape.width(),
    text: 'Booked by: ' + bookable.userName,
    fontSize,
    fontFamily,
    listening: false,
  });

  const bookStartLabel = new Text({
    x: bookable.shape.x() + padding,
    y: bookable.shape.y() + padding + fontSize * 3,
    width: bookable.shape.width(),
    text: 'From: ' + _formatLabelDate(extractDateFromDateIsoString(bookable.bookingStartDate)),
    fontSize,
    fontFamily,
    listening: false,
  });

  const bookEndLabel = new Text({
    x: bookable.shape.x() + padding,
    y: bookable.shape.y() + padding + fontSize * 4,
    width: bookable.shape.width(),
    text: 'To: ' + _formatLabelDate(extractDateFromDateIsoString(bookable.bookingEndDate)),
    fontSize,
    fontFamily,
    listening: false,
  });

  container.add(deskNameLabel);
  container.add(confirmationLabel);
  container.add(bookedByLabel);
  container.add(bookStartLabel);
  container.add(bookEndLabel);

  return container;
}

function _createLabelsForBookedBookable(bookable: Bookable): Group {
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

  const confirmationLabel = new Text({
    x: bookable.shape.x() + padding,
    y: bookable.shape.y() + padding + fontSize,
    width: bookable.shape.width(),
    text: 'Confirmation: ' + bookable.bookingConfirmationCode,
    fontSize,
    fontFamily,
    listening: false,
  });

  const bookedByLabel = new Text({
    x: bookable.shape.x() + padding,
    y: bookable.shape.y() + padding + fontSize * 2,
    width: bookable.shape.width(),
    text: 'Booked by: ' + bookable.userName,
    fontSize,
    fontFamily,
    listening: false,
  });

  const bookStartLabel = new Text({
    x: bookable.shape.x() + padding,
    y: bookable.shape.y() + padding + fontSize * 3,
    width: bookable.shape.width(),
    text: 'From: ' + _formatLabelDate(extractDateFromDateIsoString(bookable.bookingStartDate)),
    fontSize,
    fontFamily,
    listening: false,
  });

  const bookEndLabel = new Text({
    x: bookable.shape.x() + padding,
    y: bookable.shape.y() + padding + fontSize * 4,
    width: bookable.shape.width(),
    text: 'To: ' + _formatLabelDate(extractDateFromDateIsoString(bookable.bookingEndDate)),
    fontSize,
    fontFamily,
    listening: false,
  });

  container.add(deskNameLabel);
  container.add(confirmationLabel);
  container.add(bookedByLabel);
  container.add(bookStartLabel);
  container.add(bookEndLabel);

  return container;
}

function _createLabelsForDisabledBookable(bookable: Bookable): Group {
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
    text: 'From: ' + _formatLabelDate(extractDateFromDateIsoString(bookable.disabledFromDate)),
    fontSize,
    fontFamily,
    listening: false,
  });

  const disabledToLabel = new Text({
    x: bookable.shape.x() + padding,
    y: bookable.shape.y() + padding + fontSize * 3,
    width: bookable.shape.width(),
    text: 'To: ' + _formatLabelDate(extractDateFromDateIsoString(bookable.disabledToDate)),
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


function _formatLabelDate(string: string): string {
  const date = new Date(string);
  return `${date.getDate()}/${date.getMonth() + 1}/${date.getFullYear()}`;
}
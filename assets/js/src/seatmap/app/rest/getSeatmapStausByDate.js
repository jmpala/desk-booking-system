"use strict";

import {Bookable} from "../model/bookables";
import {Shape} from "konva/lib/Shape";
import {Rect} from "konva/lib/shapes/Rect";
import {getColorByState} from "../../utils/utils";
import {bookingStates} from "../enums";
import {Group} from "konva/lib/Group";
import {createBookableInformationLabels} from "../../components/ui/bookableInformatioLabelsFactory";


/**
 * Retrieves all the bookable elements for a given date by calling the internal REST API
 *
 * @param date
 * @returns {Promise<getSeatmapStausByDateResponse>}
 */
export async function getSeatmapStausByDate(date: Date): getSeatmapStausByDateResponse {
  const res = await fetch(`/sketch/${date}`);
  const json = await res.json();
  return _jsonToArrayOfBookables(json);
}

/**
 * Converts the json response into an array of Bookable objects
 *
 * @param json
 * @returns {RESTCallResponse}
 * @private
 */
function _jsonToArrayOfBookables(json: RESTCallResponse): getSeatmapStausByDateResponse {
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

    bookable.container = new Group();
    bookable.container.add(bookable.shape);

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

      if (bookable.isBookedByLoggedUser) {
        bookable.shape.fill(getColorByState(bookingStates.BOOKEDBYUSER));
      } else {
        bookable.shape.fill(getColorByState(bookingStates.BOOKED));
      }
    }

    bookable.isDisabled = b.isDisabled;
    if (bookable.isDisabled) {
      bookable.disabledFromDate = new Date(b.disabledFromDate);
      bookable.disabledToDate = new Date(b.disabledToDate);
      bookable.disabledNotes = b.disabledNotes;
      bookable.shape.fill(getColorByState(bookingStates.UNAVAILABLE));
    }

    // TODO: this is UI, do not do it here
    const labels = createBookableInformationLabels(bookable);
    if (labels) {
      bookable.container.add(labels);
    }

    return bookable;
  });

  return json;
}

/**
 * The response from the REST API, as it is
 */
type RESTCallResponse = {
  date: Date,
  bookables: Array<BookablesType>
}

/**
 * Converted response from the REST API, after mapping
 * {@link BookablesType} to {@link Bookable}
 */
export type getSeatmapStausByDateResponse = {
  date: Date,
  bookables: Array<Bookable>
}

/**
 * Declaration of a bookable returned from the REST API
 */
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


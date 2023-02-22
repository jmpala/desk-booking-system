"use strict";

import {bookingStates} from "../enums";
import {Shape} from "konva/lib/Shape";
import {Group} from "konva/lib/Group";


/**
 * Represents a bookable object inside the booking system
 */
export class Bookable {

    shape: Shape;
    container: Group;
    bookableId: number;
    bookableCode: string;
    bookableDescription: string;
    bookableCategory: string;
    userName: string;
    isBookedByLoggedUser: boolean;
    isBooked: boolean;
    bookingId: number;
    bookingConfirmationCode: string;
    bookingStartDate: Date;
    bookingEndDate: Date;
    bookingCreatedAt: Date;
    isDisabled: boolean;
    disabledFromDate: Date;
    disabledToDate: Date;
    disabledNotes: string;


    constructor(state: bookingStates) {
        this.state = state;
    }

    setEventListeners(event: Array<event>): void {
        if (event.length === 0) {
            return;
        }

        event.forEach((e) => {
            this.shape.on(e.event, e.callback);
        });
    }

    selectBookable(): void {
        this.shape.stroke('black');
        this.shape.strokeWidth(5);
        this.container.moveToTop();
    }

    unselectBookable(): void {
        this.shape.stroke('black');
        this.shape.strokeWidth(1);
    }
}

/**
 * Generic event object used to set event listeners on bookable objects
 */
type event = {
    event: string,
    callback: Function,
}
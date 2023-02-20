"use strict";

import {bookingStates} from "../enums";
import {Shape} from "konva/lib/Shape";

type event = {
    event: string,
    callback: Function,
}

export class Bookable {

    shape: Shape;
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
        if (new.target === Bookable) {
            throw new TypeError("Cannot construct bookable instances directly");
        }
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
}

export class BookableDesk extends Bookable {

    constructor(state: bookingStates) {
        super(state);
    }
}

export class BookableRoom extends Bookable {

    constructor(state: bookingStates) {
        super(state);
    }
}

export class BookableMeetingRoom extends BookableRoom {

    constructor(state: bookingStates) {
        super(state);
    }
}
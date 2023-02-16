"use strict";

import {bookingStates} from "../enums";
import {Shape} from "konva/lib/Shape";

type event = {
    event: string,
    callback: Function,
}

export class Bookable {

    uuid: string;
    category: string;
    state: bookingStates;
    shape: Shape;

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
"use strict";

import {appEnvironment, bookingStates} from "./enums";

export const config = {
    env: appEnvironment.DEBUG,
    app: {
        width: 800,
        height: 600,

        bookables: {
            state: {
                [bookingStates.AVAILABLE]: 'green',
                [bookingStates.UNAVAILABLE]: 'grey',
                [bookingStates.BOOKED]: 'red',
                [bookingStates.BOOKEDBYUSER]: 'blue',
            }
        }
    },
    debug: {
        boundaries: {
            color: 'black',
            strokeWidth: 1,
        }
    }
};
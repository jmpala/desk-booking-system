"use strict";

import {appEnvironment, bookingStates} from "./enums";

export const config = {
    env: appEnvironment.DEBUG,
    app: {
        width: 800,
        height: 600,

        map: {
            size: {
                width: 1500,
                height: 550,
            },
        },

        ui: {
            information_modal: {
                dom_id: '#desk_information_modal',
            }
        },

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
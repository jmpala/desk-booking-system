"use strict";

import {appEnvironment, bookingStates} from "./app/enums";

export const config = {
    env: appEnvironment.DEBUG,
    domElement: '#konva-container',
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
            },

            seatmapCaption: {
                size: {
                    width: 200,
                    height: 100,
                },
                position: {
                    x: 0,
                    y: 0,
                },
                backgroundColor: '#efc050',
            }
        },

        bookables: {
            state: {
                [bookingStates.AVAILABLE]: '#bada55',
                [bookingStates.UNAVAILABLE]: '#5a5a5a',
                [bookingStates.BOOKED]: '#d51961',
                [bookingStates.BOOKEDBYUSER]: '#3399ff',
            }
        }
    },
    urls: {
        editBooking: "/booking/edit?id=",
        newBooking: "/booking/new?id=",
    },
    debug: {
        boundaries: {
            color: 'black',
            strokeWidth: 1,
        }
    }
};
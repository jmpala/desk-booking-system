"use strict";

export const layers = Object.freeze({
    BACKGROUND:   Symbol("background"),
    ROOM:  Symbol("room"),
    UI: Symbol("ui"),
});

export const bookingStates = Object.freeze({
    AVAILABLE:   Symbol("available"),
    UNAVAILABLE:  Symbol("unavailable"),
    BOOKED: Symbol("booked"),
    BOOKEDBYUSER: Symbol("bookedbyuser"),
});

export const bookableCategory = Object.freeze({
    DESK:   Symbol("desk"),
    MEETINGROOM:  Symbol("meetingroom"),
    PARKING: Symbol("parking"),
});

export const appEnvironment = Object.freeze({
    DEV:   Symbol("dev"),
    DEBUG:   Symbol("debug"),
    PROD:  Symbol("prod"),
});
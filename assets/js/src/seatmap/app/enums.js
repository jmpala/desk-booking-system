"use strict";

/**
 * This enum holds all the possible layers for the seatmap component
 *
 * @type {Readonly<{UI: symbol, BACKGROUND: symbol, ROOM: symbol}>}
 */
export const layers = Object.freeze({
    ROOM:  Symbol("room"),
    UI: Symbol("ui"),
});

/**
 * This enum holds all the possible states for a bookable component
 *
 * @type {Readonly<{AVAILABLE: symbol, BOOKED: symbol, BOOKEDBYUSER: symbol, UNAVAILABLE: symbol}>}
 */
export const  bookingStates = Object.freeze({
    AVAILABLE:   Symbol("available"),
    UNAVAILABLE:  Symbol("unavailable"),
    BOOKED: Symbol("booked"),
    BOOKEDBYUSER: Symbol("bookedbyuser"),
});

/**
 * This enum holds the environment where the component is running
 *
 * @type {Readonly<{PROD: symbol, DEV: symbol, DEBUG: symbol}>}
 */
export const appEnvironment = Object.freeze({
    DEV:   Symbol("dev"),
    DEBUG:   Symbol("debug"),
    PROD:  Symbol("prod"),
});
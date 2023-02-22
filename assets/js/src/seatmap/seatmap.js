"use strict";

import {config} from "./config";
import {Layer} from "konva/lib/Layer";
import {bookingStates, layers} from "./app/enums";
import {Stage} from "konva/lib/Stage";
import {handleBookableSelectionOnClickEvent, showBookableDebugInformationOnClickEvent} from "./domEvents/bookableEvents";
import {createSeatmapCaption, createSeatmapTitle} from "./components/ui/seatmapCaptionFactory";
import {createMainRoom} from "./components/rooms/mainRoom";
import {AppState} from "./app/AppState";
import {Bookable} from "./app/model/bookables";
import {preventMapOutOfBoundsOnDragmoveEvent} from "./domEvents/layerEvents";
import {deselectBookableOnClickEvent, resizeStageOnWindowResizeEvent} from "./domEvents/stageEvents";
import {getColorByState} from "./utils/utils";


// Setting up the date picker
const datePiclerId = 'datePicker';
const datePicker = document.getElementById(datePiclerId);
if (!datePicker) {
    throw new Error(`Date picker not found, please check your DOM, this component needs an id="${datePiclerId}"`);
}
datePicker.value = new Date().toISOString().split('T')[0];
datePicker.addEventListener('change', async e => {
    e.stopPropagation();
    updateSeatmap(new Date(e.target.value).toISOString());
});

const submitBtn = document.querySelector('#konva-submit');
if (!submitBtn) {
    throw new Error(`Submit Button not found, please check your DOM, this component needs an id="${submitBtn}"`);
}
submitBtn.value = 'Please, select booking';
submitBtn.style.color = getColorByState(bookingStates.UNAVAILABLE);
submitBtn.disabled = true;

export const appState = new AppState();

const parentContainer = document.querySelector(config.domElement).getBoundingClientRect();
if (!parentContainer) {
    throw new Error(`Parent container not found, please check your DOM, this component needs an id="${parentContainer}"`);
}
const stage = new Stage({
    container: config.domElement,
    x: 0,
    y: 0,
    width: parentContainer.width,
    height: config.app.height,
    draggable: false,
});
deselectBookableOnClickEvent(stage, appState);
resizeStageOnWindowResizeEvent(stage);

// create layers and defining map size
const appLayers = {
    [layers.ROOM]: new Layer().name(layers.ROOM).draggable(true),
    [layers.UI]: new Layer().name(layers.UI).draggable(false),
};
// layers order
stage.add(appLayers[layers.ROOM]);
stage.add(appLayers[layers.UI]);
preventMapOutOfBoundsOnDragmoveEvent(appLayers[layers.ROOM], stage);

// register elements to each layer
(async (stage: Stage, appLayers: Layer) => {
    appLayers[layers.ROOM].add(createMainRoom());
    console.log(appLayers[layers.ROOM])

    await updateSeatmap(new Date().toISOString());

    // Adding UI components
    appLayers[layers.UI].add(createSeatmapTitle({
        title: 'Big Room',
        x: 0, y: 0,
        fontSize: 30,
        padding: 20,
    }));
    appLayers[layers.UI].add(createSeatmapCaption(stage));

})(stage, appLayers)

/**
 * Updates the component for the given date
 *
 * @param date
 * @returns {Promise<void>}
 */
async function updateSeatmap(date: Date): void {
    await appState.updateStateByDate(date);
    const bookables: Array<Bookable> = appState.getBookings();
    bookables.forEach((b: Bookable) => {
        showBookableDebugInformationOnClickEvent(b);
        handleBookableSelectionOnClickEvent(b, appState);
        appLayers[layers.ROOM].add(b.container);
    });
}

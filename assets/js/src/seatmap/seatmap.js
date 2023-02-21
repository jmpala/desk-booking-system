"use strict";

import {config} from "./config";
import {Layer} from "konva/lib/Layer";
import {layers} from "./app/enums";
import {Stage} from "konva/lib/Stage";
import {selectSelfOnTheAppOnClickEvent, showInformationModalOnClickEvent} from "./domEvents/bookableEvents";
import {createSeatmapCaption, createSeatmapTitle} from "./components/ui/seatmapCaptionFactory";
import {createMainRoom} from "./components/rooms/mainRoom";
import {AppState} from "./app/AppState";
import {Bookable} from "./app/model/bookables";
import {boundToOffsetMapOnDragmoveEvent} from "./domEvents/layerEvents";
import {unselectBookableLayerOnClickEvent} from "./domEvents/stageEvents";

export const app = new AppState();

const datePicker = document.getElementById('datePicker');
if (!datePicker) {
    throw new Error('Date picker not found, please check your DOM, this component needs one!!!');
}
datePicker.value = new Date().toISOString().split('T')[0];

const stage = new Stage({
    container: config.domElement,
    width: window.innerWidth,
    height: config.app.map.size.height,
    draggable: false,
});

// create layers and defining map size
const appLayers = {
    [layers.ROOM]: new Layer().name(layers.ROOM).draggable(true),
    [layers.UI]: new Layer().name(layers.UI).draggable(false),
};

datePicker.addEventListener('change', async e => {
    e.preventDefault();
    e.stopPropagation();
    updateStateByDate(new Date(e.target.value).toISOString());
});

boundToOffsetMapOnDragmoveEvent(appLayers[layers.ROOM]);
// layers order
stage.add(appLayers[layers.ROOM]);
stage.add(appLayers[layers.UI]);

// register elements to each layer
(async (stage: Stage, appLayers: Layer) => {
    unselectBookableLayerOnClickEvent(stage, app);

    appLayers[layers.ROOM].add(createMainRoom());

    await updateStateByDate(new Date().toISOString());

    // Adding UI components
    appLayers[layers.UI].add(createSeatmapTitle({
        title: 'Big Room',
        x: 0, y: 0,
        fontSize: 30,
        padding: 20,
    }));
    appLayers[layers.UI].add(createSeatmapCaption(appLayers[layers.ROOM]));

})(stage, appLayers)

async function updateStateByDate(date: Date): void {
    await app.updateStateByDate(date);
    const bookables: Array<Bookable> = app.getBookings();
    bookables.forEach((b: Bookable) => {
        showInformationModalOnClickEvent(b);
        selectSelfOnTheAppOnClickEvent(b, app);
        appLayers[layers.ROOM].add(b.container);
    });
}

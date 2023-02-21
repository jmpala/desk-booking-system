"use strict";

import {config} from "./config";
import {Layer} from "konva/lib/Layer";
import {layers} from "./app/enums";
import {Stage} from "konva/lib/Stage";
import {selectSelfOnTheAppOnClickEvent, showInformationModalOnClickEvent} from "./domEvents/bookableEvents";
import {createSeatmapCaption, createSeatmapTitle} from "./components/ui/componentsFactory";
import {createMainRoom} from "./components/rooms/mainRoom";
import {AppState} from "./app/AppState";
import {Bookable} from "./app/model/bookables";
import {unselectBookableLayerOnClickEvent} from "./domEvents/layerEvents";

const app = new AppState();

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

// layers order
stage.add(appLayers[layers.ROOM]);
stage.add(appLayers[layers.UI]);

// register elements to each layer
(async (stage: Stage, appLayers: Layer) => {

    appLayers[layers.ROOM].add(createMainRoom());
    unselectBookableLayerOnClickEvent(stage, app);

    const bookables = await updateStateByDate(new Date().toISOString());

    bookables.forEach(b => {
        showInformationModalOnClickEvent(b);
        selectSelfOnTheAppOnClickEvent(b, app);
        appLayers[layers.ROOM].add(b.shape);
    });

    // Adding UI components
    appLayers[layers.UI].add(createSeatmapTitle({
        title: 'Big Room',
        x: 0, y: 0,
        fontSize: 30,
        padding: 20,
    }));
    appLayers[layers.UI].add(createSeatmapCaption(appLayers[layers.ROOM]));

})(stage, appLayers)

async function updateStateByDate(date: Date): Array<Bookable> {
    await app.updateStateByDate(date);
    return app.getBookings();
}
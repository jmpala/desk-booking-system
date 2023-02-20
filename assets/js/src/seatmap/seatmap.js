"use strict";

import {StageWrapper} from "./wrappers";
import {config} from "./config";
import {Layer} from "konva/lib/Layer";
import {layers} from "./enums";
import {getSeatmapStausByDate} from "./rest/restCalls";
import {createNewBookableDesk} from "./bookables/bookablesFactory";
import {Stage} from "konva/lib/Stage";
import {Rect} from "konva/lib/shapes/Rect";
import {showInformationModalOnClick} from "./bookables/bookableEvents";
import {createSeatmapCaption, createSeatmapTitle} from "./ui/componentsFactory";

const stage = new StageWrapper({
    container: 'container',
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

    // creating office map
    // TODO: map making functionality
    let $officeFloor = new Rect({
        x: 0,
        y: 0,
        width: config.app.map.size.width,
        height: config.app.map.size.height,
        fill: '#e3dbc3',
        stroke: 'black',
        strokeWidth: 1,
    });

    appLayers[layers.ROOM].add($officeFloor);

    let $seatmapStatus = await getSeatmapStausByDate();

    $seatmapStatus.bookables = $seatmapStatus.bookables.map(b => {
        const booking = createNewBookableDesk(b);

        showInformationModalOnClick(booking);
        appLayers[layers.ROOM].add(booking.shape);
        return booking;
    })

    // Adding UI components
    appLayers[layers.UI].add(createSeatmapTitle({
        title: 'Big Room',
        x: 0, y: 0,
        fontSize: 30,
        padding: 20,
    }));
    appLayers[layers.UI].add(createSeatmapCaption(appLayers[layers.ROOM]));

})(stage, appLayers)
"use strict";

// - The objects should have x, y, width, height and category
// - the category defines characteristics of the object
// - the object needs states like [selected, unselected, disabled]


import {StageWrapper} from "./wrappers";
import {config} from "./config";
import {Layer} from "konva/lib/Layer";
import {appEnvironment, bookingStates, layers} from "./enums";
import {Text} from "konva/lib/shapes/Text";
import {drawDebugLinesNode} from "./utils/debug";
import {getSeatmapStausByDate, getBookingsFromDate} from "./rest/restCalls";
import {createNewBookableDesk} from "./bookables/bookablesFactory";
import {Stage} from "konva/lib/Stage";
import {Bookable} from "./bookables/bookables";
import {Rect} from "konva/lib/shapes/Rect";
import {showInformationModalOnClick} from "./bookables/bookableEvents";

const stage = new StageWrapper({
    container: 'container',
    width: window.innerWidth,
    height: window.innerHeight,
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

    const text = new Text({
        x: 10,
        y: 10,
        text: 'Big Room',
        fontSize: 30,
        fontFamily: 'Roboto',
        fill: 'black'
    });

    appLayers[layers.UI].add(text);

    if (config.env === appEnvironment.DEBUG) {
        $seatmapStatus.bookables.forEach(c => c instanceof Bookable ? drawDebugLinesNode(c.shape, 'black') : null);
    }
})(stage, appLayers)
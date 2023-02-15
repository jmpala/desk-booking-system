"use strict";

// - The objects should have x, y, width, height and category
// - the category defines characteristics of the object
// - the object needs states like [selected, unselected, disabled]


import {StageWrapper} from "./wrappers";
import {config} from "./config";
import {Layer} from "konva/lib/Layer";
import {appEnvironment, layers, bookingStates} from "./enums";
import {Rect} from "konva/lib/shapes/Rect";
import {Text} from "konva/lib/shapes/Text";
import {drawDebugLinesNode} from "./utils/debug";
import {createNewBookableDesk} from "./bookables/bookablesFactory";

const stage = new StageWrapper({
    container: 'container',
    width: config.app.width,
    height: config.app.height,
    draggable: false,
});

// create layers
const appLayers = {
    [layers.BACKGROUND]: new Layer().name(layers.BACKGROUND).zIndex(2).draggable(true).size({width: config.app.width, height: config.app.height}),
    [layers.ROOM]: new Layer().name(layers.ROOM).zIndex(1).draggable(true).size({width: config.app.width, height: config.app.height}),
    [layers.UI]: new Layer().name(layers.UI).zIndex(0).draggable(false).size({width: config.app.width, height: config.app.height}),
};

// layers order
stage.add(appLayers[layers.BACKGROUND]);
stage.add(appLayers[layers.ROOM]);
stage.add(appLayers[layers.UI]);

// get all bookables from the DB

// register elements to each layer
const rec1 = createNewBookableDesk({
    uuid: "123456789",
    x: 100,
    y: 100,
    width: 100,
    height: 100,
    state: bookingStates.UNAVAILABLE,
});


appLayers[layers.ROOM].add(rec1);

const text = new Text({
    x: 10,
    y: 10,
    text: 'Hello World!',
    fontSize: 30,
    fontFamily: 'Calibri',
    fill: 'green'
});

appLayers[layers.UI].add(text);

if (config.env === appEnvironment.DEBUG) {
    const layer = appLayers[layers.ROOM];
    const children = layer.children;
    drawDebugLinesNode(layer);
    children.forEach(c => drawDebugLinesNode(c));
}

// run the app
stage.draw();
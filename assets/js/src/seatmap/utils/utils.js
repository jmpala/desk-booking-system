"use strict";

import {Shape} from "konva/lib/Shape";
import {Stage} from "konva/lib/Stage";
import Konva from "konva/lib";
import {Node} from "konva/lib/Node";
import {bookingStates} from "../app/enums";
import {config} from "../config";

export function drawDebugBoundaries(stage: Stage) {
    const size = stage.getSize();
    const boundaryLine = new Konva.Line({
        points: [0, 0, size.width, 0, size.width, size.height, 0, size.height, 0, 0],
        stroke: 'red',
        strokeWidth: 1,
        lineJoin: 'round',
        dash: [10, 10],
        closed: true
    });

    stage.getLayers()[0].add(boundaryLine);
}

export function isInsideStage(shape: Shape, stage: Stage): boolean {
    const position = shape.getAbsolutePosition(stage);
    const size = shape.getSize();
    console.log(position, stage.width(), stage.height());
    return position.x > 0 && position.y > 0
        && position.x + size.width < stage.width()
        && position.y + size.height < stage.height();
}

export function isInsideStageXAxis(shape: Shape, stage: Stage): boolean {
    const position = shape.getAbsolutePosition(stage);
    const size = shape.getSize();
    return position.x > 0 && position.x + size.width < stage.width();
}

export function isInsideStageYAxis(shape: Shape, stage: Stage): boolean {
    const position = shape.getAbsolutePosition(stage);
    const size = shape.getSize();
    return position.y > 0 && position.y + size.height < stage.height();
}

export function boundNodeMovementToParentNode(node: Node, parentNode: Node) {
    const position = node.getAbsolutePosition(parentNode);
    const size = node.getSize();
    if (position.x < 0) {
        node.x(0);
    }
    if (position.y < 0) {
        node.y(0);
    }
    if (position.x + size.width > parentNode.width()) {
        node.x(parentNode.width() - size.width);
    }
    if (position.y + size.height > parentNode.height()) {
        node.y(parentNode.height() - size.height);
    }
}

export function getStageFromNode(node: Node): Stage {
    let parent = node.getParent();
    while (parent) {
        if (parent instanceof Stage) {
            return parent;
        }
        parent = parent.getParent();
    }
    throw new Error('No stage found');
}

export function getColorByState(state: bookingStates): string {
    switch (state) {
        case bookingStates.AVAILABLE:
            return config.app.bookables.state[bookingStates.AVAILABLE];
        case bookingStates.UNAVAILABLE:
            return config.app.bookables.state[bookingStates.UNAVAILABLE];
        case bookingStates.BOOKED:
            return config.app.bookables.state[bookingStates.BOOKED];
        case bookingStates.BOOKEDBYUSER:
            return config.app.bookables.state[bookingStates.BOOKEDBYUSER];
        default:
            throw new Error(`Booking state ${state} is not registered`);
    }
}
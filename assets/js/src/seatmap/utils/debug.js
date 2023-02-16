"use strict";

import {Node} from "konva/lib/Node";
import {Line} from "konva/lib/shapes/Line";
import {config} from "../config";
import {Shape} from "konva/lib/Shape";
import {Layer} from "konva/lib/Layer";

export function drawDebugLinesNode(node: Node, color: ?string = null) {
    const strokeColor = color ?? config.debug.boundaries.color;
    if (node instanceof Shape) {
        console.log("Shape", strokeColor);
        node.stroke(strokeColor);
        node.strokeWidth(config.debug.boundaries.strokeWidth);
        return;
    }

    if (node instanceof Layer) {
        console.log("Layer", strokeColor);
        const {x, y} = node.position();
        const {width, height} = node.size();
        const line = new Line({
            points: [x, y, width, y, width, height, x, height, x, y],
            stroke: strokeColor,
            strokeWidth: config.debug.boundaries.strokeWidth,
        });
        node.add(line);
    }
}
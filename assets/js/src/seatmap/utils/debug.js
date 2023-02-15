"use strict";

import {Node} from "konva/lib/Node";
import {Line} from "konva/lib/shapes/Line";
import {config} from "../config";
import {Shape} from "konva/lib/Shape";
import {Layer} from "konva/lib/Layer";

export function drawDebugLinesNode(node: Node) {
    console.log(node);
    if (node instanceof Shape) {
        node.stroke(config.debug.boundaries.color);
        node.strokeWidth(config.debug.boundaries.strokeWidth);
        return;
    }

    if (node instanceof Layer) {
        const {x, y} = node.position();
        const {width, height} = node.size();
        const line = new Line({
            points: [x, y, width, y, width, height, x, height, x, y],
            stroke: config.debug.boundaries.color,
            strokeWidth: config.debug.boundaries.strokeWidth,
        });
        node.add(line);
    }
}
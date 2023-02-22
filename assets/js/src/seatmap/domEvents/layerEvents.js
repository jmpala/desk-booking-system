"use strict";

import {config} from "../config";
import {Layer} from "konva/lib/Layer";
import {Stage} from "konva/lib/Stage";


/**
 * Bounds the drag of the map to an offset, preventing the map from being
 * dragged out of the viewport
 *
 * @param layer
 */
export function preventMapOutOfBoundsOnDragmoveEvent(layer: Layer, stage: Stage): void {
  const offset = 10;
  layer.on('dragmove', (e) => {
    e.cancelBubble = true;
    const target = e.target;

    const x = target.x();
    const y = target.y();
    const xw = target.x() + config.app.map.size.width;
    const yh = target.y() + config.app.map.size.height;

    const xFSide = stage.width();
    const yFSide = config.app.height;

    if (config.app.map.size.width <= xFSide) {
      target.x(xFSide / 2 - config.app.map.size.width / 2);
    } else {
      if (x > offset) {
        target.x(offset)
      }

      if (xw < xFSide - offset) {
        target.x(xFSide - config.app.map.size.width - offset)
      }
    }

    if (config.app.map.size.height <= yFSide) {
      target.y(yFSide / 2 - config.app.map.size.height / 2);
    } else {
      if (y > offset) {
        target.y(offset)
      }

      if (yh < yFSide - offset) {
        target.y(yFSide - config.app.map.size.height - offset)
      }
    }
  });
}
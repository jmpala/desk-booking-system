"use strict";

import {config} from "../config";
import {Layer} from "konva/lib/Layer";


/**
 * Bounds the drag of the map to an offset, preventing the map from being
 * dragged out of the viewport
 *
 * @param layer
 */
export function preventMapOutOfBoundsOnDragmoveEvent(layer: Layer): void {
  const offset = 10;
  layer.on('dragmove', (e) => {
    e.cancelBubble = true;
    const target = e.target;

    const x = target.x();
    const y = target.y();
    const xw = target.x() + config.app.map.size.width;
    const yh = target.y() + config.app.map.size.height;

    console.log(x, y, xw, yh)

    if (x - offset >= 0) {
      target.x(offset)
    }

    if (y - offset >= 0) {
      target.y(offset)
    }

    if (window.innerWidth >= xw + offset) {
      target.x(window.innerWidth - config.app.map.size.width - offset)
    }

    if (config.app.map.size.height >= yh + offset) {
      target.y(config.app.map.size.height - config.app.map.size.height - offset)
    }
  });
}
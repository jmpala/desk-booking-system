"use strict";

import {Group} from "konva/lib/Group";
import {config} from "../../config";
import {Rect} from "konva/lib/shapes/Rect";

export function createMainRoom(): Group {
  const container: Group = new Group({
    x: 0,
    y: 0,
    width: config.app.map.size.width,
    height: config.app.map.size.height,
    fill: '#e3dbc3',
    stroke: 'black',
    strokeWidth: 1,
  });

  container.add(createBigRoom());

  return container;
}

function createBigRoom(): Rect {
  return new Rect({
    x: 0,
    y: 0,
    width: config.app.map.size.width,
    height: config.app.map.size.height,
    fill: '#e3dbc3',
    stroke: 'black',
    strokeWidth: 1,
  });
}
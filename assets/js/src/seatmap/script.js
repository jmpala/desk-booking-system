"use strict";

import Konva from 'konva';
import {
  boundNodeMovementToParentNode,
  drawDebugBoundaries, getStageFromNode,
} from './utils';
import {RectWrapper, StageWrapper} from './wrappers';

const width = 800;
const height = 600;

const stage = new StageWrapper({
  container: 'container',
  width,
  height,
  draggable: true,
});

stage.getContent().style.border = '1px solid black';

const layer = new Konva.Layer();

const rect1 = new RectWrapper({
  x: 0,
  y: 0,
  width: 200,
  height: 200,
  fill: 'red',
  draggable: true,
});

const rect2 = new Konva.Rect({
  x: 300,
  y: 0,
  width: 200,
  height: 200,
  fill: 'blue',
});

rect1.on('dragstart', (event) => {
  event.target.oldPosition = event.target.getAbsolutePosition(getStageFromNode(event.target));
});

rect1.on('dragend', (event) => {
  boundNodeMovementToParentNode(event.target, getStageFromNode(event.target));
});

rect1.on('dragmove', (event) => {
  boundNodeMovementToParentNode(event.target, getStageFromNode(event.target));
});

const offset = 30;

stage.on('dragstart', (event) => {
  event.target.oldPosition = event.target.getPosition();
  // console.log(event.target.getPosition(), event.target.getSize());
});

stage.on('dragend', (event) => {
  console.log(event.target.getPosition(), event.target.getSize());
  if (event.target.x() < -offset) {
    event.target.x(-offset);
  }

  if (event.target.y() < -offset) {
    event.target.y(-offset);
  }

  if (event.target.x() > width - event.target.width() + offset) {
    event.target.x(width - event.target.width() + offset);
  }

  if (event.target.y() > height - event.target.height() + offset) {
    event.target.y(height - event.target.height() + offset);
  }
});

stage.on('dragmove', (event) => {
  console.log(event.target.getPosition(), event.target.getSize());
  if (event.target.x() < -offset) {
    event.target.x(-offset);
  }

  if (event.target.y() < -offset) {
    event.target.y(-offset);
  }

  if (event.target.x() > width - event.target.width() + offset) {
    event.target.x(width - event.target.width() + offset);
  }

  if (event.target.y() > height - event.target.height() + offset) {
    event.target.y(height - event.target.height() + offset);
  }
});

stage.add(layer);
drawDebugBoundaries(stage);

layer.add(rect1);
layer.add(rect2);

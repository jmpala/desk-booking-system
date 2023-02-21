"use strict";

import {Rect} from "konva/lib/shapes/Rect";
import {Group} from "konva/lib/Group";
import {config} from "../../config";
import {Text} from "konva/lib/shapes/Text";
import {getColorByState} from "../../utils/utils";
import {bookingStates} from "../../app/enums";

export function createSeatmapCaption(): Group {
  const x: number = window.innerWidth - config.app.ui.seatmapCaption.size.width;
  const y: number = config.app.height - config.app.ui.seatmapCaption.size.height
  const width: number = config.app.ui.seatmapCaption.size.width;
  const height: number = config.app.ui.seatmapCaption.size.height;
  const fill: number = config.app.ui.seatmapCaption.backgroundColor;

  let textCreated: number = 0;
  const padding: number = 10;
  const textSize: number = 20;

  const container: Group = new Group({
    x,
    y,
    width,
    height,
  });

  const background: Rect = new Rect({
    x: 0,
    y: 0,
    width,
    height,
    fill,
  });

  container.add(background);
  container.add(_createCaptionTextWithReferenceColor({
    text: "Available",
    textSize: textSize,
    textColor: "black",
    padding: padding,
    squareColor: getColorByState(bookingStates.AVAILABLE),
    position: textCreated++,
  }));

  container.add(_createCaptionTextWithReferenceColor({
    text: "Booked",
    textSize: textSize,
    textColor: "black",
    padding: padding,
    squareColor: getColorByState(bookingStates.BOOKED),
    position: textCreated++,
  }));

  container.add(_createCaptionTextWithReferenceColor({
    text: "Booked by you",
    textSize: textSize,
    textColor: "black",
    padding: padding,
    squareColor: getColorByState(bookingStates.BOOKEDBYUSER),
    position: textCreated++,
  }));

  container.add(_createCaptionTextWithReferenceColor({
    text: "Disabled",
    textSize: textSize,
    textColor: "black",
    padding: padding,
    squareColor: getColorByState(bookingStates.UNAVAILABLE),
    position: textCreated++,
  }));

  window.addEventListener("resize", () => {
    container.x(window.innerWidth - config.app.ui.seatmapCaption.size.width);
  });

  container.addEventListener('pointerenter',e => {
    container.opacity(0.5);
  });

  container.addEventListener('pointerleave',e => {
    container.opacity(1);
  });

  container.listening(false);

  return container;
}

type CaptionTextWithReferenceColorConfiguration = {
  text: string,
  textSize: number,
  textColor: string,
  squareColor: string,
  padding?: number,
  position: number,
};

function _createCaptionTextWithReferenceColor(params: CaptionTextWithReferenceColorConfiguration): Group {

  const padding: number = params.padding ?? 0;

  const container: Group = new Group({
    x: padding,
    y: (params.textSize * params.position) + padding,
    width: config.app.ui.seatmapCaption.size.width,
    height: params.textSize,
  });

  const textUI = new Text({
    text: params.text,
    x: params.textSize + padding,
    fontSize: params.textSize,
    fontFamily: "Roboto",
    fill: params.textColor,
  });

  const squareColorUI = new Rect({
    width: params.textSize,
    height: params.textSize,
    fill: params.squareColor,
    stroke: "black",
    strokeWidth: 1,
  });

  container.add(textUI);
  container.add(squareColorUI);

  return container;
}

type seatMapTitleConfiguration = {
  title: string,
  x: number,
  y: number,
  fontSize: number,
  padding?: number,
};

export function createSeatmapTitle(params: seatMapTitleConfiguration): Group {
  const container: Group = new Group();

  const padding: number = params.padding ?? 0;

  const text = new Text({
    x: params.x + padding,
    y: params.y + padding,
    text: params.title,
    fontSize: params.fontSize,
    fontFamily: 'Roboto',
    fill: 'black'
  });

  container.add(text);

  return container;
}
"use strict";

import {Stage} from "konva/lib/Stage";
import {AppState} from "../app/AppState";
import {config} from "../config";


/**
 * Bounds the {@param stage} to the {@param appState} so when the stage
 * recieved a click event, deselects the selected bookable
 *
 * @param stage
 * @param appState
 */
export function deselectBookableOnClickEvent(stage: Stage, appState: AppState): void {
  stage.addEventListener("click", (event) => {
    appState.setSelectedBooking(null);
  });
}

/**
 * Resizes the {@param stage} to the parent container's size
 *
 * @param stage
 */
export function resizeStageOnWindowResizeEvent(stage: Stage): void {
  window.addEventListener("resize", () => {
    const container = document.querySelector(config.domElement).getBoundingClientRect();
    stage.width(container.width);
    stage.draw();
  });
}
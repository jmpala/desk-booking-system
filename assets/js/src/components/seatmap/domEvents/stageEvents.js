"use strict";

import {Stage} from "konva/lib/Stage";
import {AppState} from "../app/AppState";
import {config} from "../config";
import {getColorByState} from "../utils/utils";
import {bookingStates} from "../app/enums";


/**
 * Bounds the {@param stage} to the {@param appState} so when the stage
 * recieved a click event, deselects the selected bookable
 *
 * @param stage
 * @param appState
 */
export function deselectBookableOnClickEvent(stage: Stage, appState: AppState): void {
  stage.addEventListener("click", (event) => {
    _disableButton(appState.isReadonly);
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


// TODO: Refactor, almost same logic on bookableEvents.js::_changeButton()
function _disableButton(isReadOnly: boolean): void {
  const submitbtn = document.querySelector('#konva-submit');

  if (isReadOnly) return;

  submitbtn.href = "javascript:void(0)";
  submitbtn.innerHTML = "Please, select booking";
  submitbtn.style.backgroundColor = getColorByState(bookingStates.UNAVAILABLE);
  submitbtn.style.color = "black";
}
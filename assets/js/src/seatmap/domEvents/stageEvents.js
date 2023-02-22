"use strict";

import {Stage} from "konva/lib/Stage";
import {AppState} from "../app/AppState";


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
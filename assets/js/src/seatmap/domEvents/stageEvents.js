"use strict";

import {Stage} from "konva/lib/Stage";
import {AppState} from "../app/AppState";

export function unselectBookableLayerOnClickEvent(stage: Stage, app: AppState): void {
  stage.addEventListener("click", (event) => {
    app.setSelectedBooking(null);
  });
}
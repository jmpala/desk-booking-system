"use strict";

import {AppState} from "../app/AppState";
import {Stage} from "konva/lib/Stage";

export function unselectBookableLayerOnClickEvent(stage: Stage, app: AppState): void {
  stage.addEventListener("click", (event) => {
      app.setSelectedBooking(null);
  });
}

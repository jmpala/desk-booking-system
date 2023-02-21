"use strict";

import {Bookable} from "../app/model/bookables";
import {config} from "../config";
import {AppState} from "../app/AppState";

export function showInformationModalOnClickEvent(bookable: Bookable): void {
    const modal = document.querySelector(`${config.app.ui.information_modal.dom_id}`);

    if (modal == null) {
        throw new Error(`DOM-Element with id="${config.app.ui.information_modal.dom_id}" not found.`);
    }

    bookable.setEventListeners([{
        event: "click",
        callback: (event) => {
          event.cancelBubble = true;
          console.log(bookable);
        }
    }]);
}

export function selectSelfOnTheAppOnClickEvent(bookable: Bookable, app: AppState): void {
bookable.setEventListeners([{
        event: "click",
        callback: (event) => {
          event.cancelBubble = true;
          app.setSelectedBooking(bookable);
        }
    }]);
}
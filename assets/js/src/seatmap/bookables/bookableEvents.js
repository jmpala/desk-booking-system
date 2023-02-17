"use strict";

import {Bookable} from "./bookables";
import {config} from "../config";

export function showInformationModalOnClick(bookable: Bookable): void {
    const modal = document.querySelector(`${config.app.ui.information_modal.dom_id}`);

    if (modal == null) {
        throw new Error(`DOM-Element with id="${config.app.ui.information_modal.dom_id}" not found.`);
    }

    bookable.setEventListeners([{
        event: "click",
        callback: (event) => {
            // Show different information depending on the category of the bookable.
            console.log(bookable);
        }
    }]);
}
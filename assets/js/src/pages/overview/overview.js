"use strict";

import {appState} from "../../components/seatmap/seatmap.js";

import {extractDateFromDateIsoString} from "../../components/seatmap/utils/utils";


const moveBack: HTMLElement = document.getElementById("moveBack");
const moveForward: HTMLElement = document.getElementById("moveForward");
const datePicker: HTMLElement = document.getElementById("datePicker");


moveBack.addEventListener("click", moveDayBack);
moveForward.addEventListener("click", moveDayForward);


function moveDayBack() {
  const newDate: Date = new Date(datePicker.value);
  newDate.setDate(newDate.getDate() - 1);
  datePicker.value = extractDateFromDateIsoString(newDate);
  fireChangeEvenOnElement(datePicker);
}


function moveDayForward() {
  const newDate: Date = new Date(datePicker.value);
  newDate.setDate(newDate.getDate() + 1);
  datePicker.value = extractDateFromDateIsoString(newDate);
  fireChangeEvenOnElement(datePicker);
}


function fireChangeEvenOnElement(element: HTMLElement) {
  const event = new Event("change");
  element.dispatchEvent(event);
}
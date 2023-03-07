"use strict";

const bookingFrom: HTMLFormElement = document.getElementById("newBookingForm");
const availableBookables: HTMLElement = document.getElementById("allBookables");
const fromDateDOMElement: HTMLElement = document.getElementById("fromDate");
const toDateDOMElement: HTMLElement = document.getElementById("toDate");
const submitBtn: HTMLElement = document.getElementById("submitBtn");


fromDateDOMElement.addEventListener("change", limitMinimunToDate);
fromDateDOMElement.addEventListener("change", validateButtonSate);
toDateDOMElement.addEventListener("change", validateButtonSate);
submitBtn.addEventListener('click', checkAvailabilityBeforeSubmit);


function limitMinimunToDate(): void {
  const fromDate: Date = new Date(fromDateDOMElement.value);
  const toDate: Date = new Date(toDateDOMElement.value);
  if (fromDate > toDate) {
    toDateDOMElement.value = fromDateDOMElement.value;
  }
  toDateDOMElement.min = fromDateDOMElement.value;
}


function validateButtonSate(): void {
  if (fromDateDOMElement.value && toDateDOMElement.value) {
    submitBtn.disabled = false;
  } else {
    submitBtn.disabled = true;
  }
}


async function checkAvailabilityBeforeSubmit(e: Event): void {
  e.preventDefault();
  const fromDate: Date = new Date(fromDateDOMElement.value);
  const toDate: Date = new Date(toDateDOMElement.value);

  const isAvailable: boolean = await isBookableAvailabilityBySelectedDatesRESTCall(fromDate, toDate);

  if (isAvailable) {
    bookingFrom.submit();
  }

  else {
    alert("The selected bookable is not available for the selected dates");
  }
}


async function isBookableAvailabilityBySelectedDatesRESTCall(from: Date, to: Date): boolean {
  const selectedId = availableBookables.value;
  const res: Response = await fetch(`/api/booking/${selectedId}/availability`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify({
      from: from,
      to: to
    })
  });

  const data: [] = await res.json();

  if (data.length > 0)
    return false;
  else
    return true;
}
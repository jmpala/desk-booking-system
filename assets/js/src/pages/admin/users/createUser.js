"use strict";

const form: HTMLFormElement = document.getElementById("createUserForm");
const emailField: HTMLInputElement = document.getElementById("user_form_email");
const formSubmit: HTMLButtonElement = document.getElementById("user_form_submit");
const confirmationBtn: HTMLButtonElement = document.getElementById("confirmationBtn");
const emailLabel: HTMLElement = document.getElementById("newUserEmail");

form.addEventListener("submit", handleConfirmationMessage);
formSubmit.addEventListener("click", handleModalDisplay);
confirmationBtn.addEventListener("click", handleConfirmationMessage);

function handleConfirmationMessage(event: Event) {
  event.preventDefault();
  const target: HTMLElement = event.target;

  if (target.id === "user_form_submit"
    || emailField.value === '') return;

  form.submit();
}

function handleModalDisplay(event: Event) {
  event.preventDefault();
  emailLabel.innerText = emailField.value;
}
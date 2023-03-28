'use strict';

import {validatePassword, validateEmail} from './validation';


// DOM input
const emailInput = document.querySelector('#email');
const passwordInput = document.querySelector('#password');
const formBtn = document.querySelector('#submit-btn');


// DOM error
const errorAlert = document.querySelector('.error-display');
const errorDisplay = document.querySelector('.error-display ul');


// Listeners
formBtn.addEventListener('click', submitLoginForm);


// Functions
function submitLoginForm(e) {

    const email = emailInput.value;
    const password = passwordInput.value;

    let error = [];
    _resetErrorDisplay();

    if(!email) {
        _displayError(emailInput, error, 'Email field is empty')
    }

    if (!password) {
        _displayError(passwordInput, error, 'Password field is empty');
    }

    if (!validateEmail(email) && email) {
        _displayError(emailInput, error, 'Email format is not valid')
    }

    if (!validatePassword(password) && password) {
        _displayError(passwordInput, error, 'Password format is not valid');
    }

    if (error.length > 0) {
        e.preventDefault();
        error.forEach(e => errorDisplay.innerHTML += `<li>${e}</li>`)
    }
}

function _displayError(domElement: HTMLElement, errorArray: [], errorDescription: string) {
    errorAlert.classList.add('error-display__show');
    domElement.classList.add('loginform__error');
    domElement.focus();
    errorArray.push(errorDescription);
}

function _resetErrorDisplay() {
    emailInput.classList.remove('loginform__error');
    passwordInput.classList.remove('loginform__error');
    errorAlert.classList.remove('error-display__show')
    errorDisplay.innerHTML = '';
}
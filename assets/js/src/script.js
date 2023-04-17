"use strict";

import "bootstrap/dist/css/bootstrap.css";
import "bootstrap/dist/js/bootstrap.bundle.js";

import '../../sass/style.scss';

const selectUserPage = (function () : void {
    const submitBtn: HTMLButtonElement = document.getElementById('submitBtn');
    const userSelect: HTMLSelectElement = document.getElementById('userSelect');

    submitBtn.addEventListener('click', onSubmitBtnClick);

    function onSubmitBtnClick(event: Event): void {
        const target: HTMLButtonElement = event.target;
        if (target.id !== 'submitBtn') return;
        const userId = userSelect.value;
        window.location.href = window.location.origin + `/planning/${userId}`;
    }
});

const planningPage = (function (): void {
    const userSelect: HTMLSelectElement = document.getElementById('userSelect');

    const table: HTMLElement = document.getElementById('bookingsTable');
    if (table) {
        const pastCheck: HTMLInputElement = document.getElementById('pastBookings');
        const selectedColumn: HTMLElement = document.querySelector('[data-col-selected="true"]');
        const iconSelected: HTMLElement = selectedColumn.querySelector('i');
        const deleteConmfirmationModal = document.getElementById('deleteConfirmation')

        iconSelected.classList.remove('bi-arrow-down-up');
        if (selectedColumn.dataset.colOrder === 'asc') {
            iconSelected.classList.add('bi-arrow-down');
        } else {
            iconSelected.classList.add('bi-arrow-up');
        }

        table.addEventListener('click', orderTable);
        table.addEventListener('click', onClickEditBooking);
        pastCheck.addEventListener('change', onCheckedPastBookings);
        deleteConmfirmationModal.addEventListener('show.bs.modal', onShowDeleteConfirmationModal);

        function orderTable(event: Event): void {
            const target: HTMLElement = event.target;

            if (!target.classList.contains('bi')
              && !target.classList.contains('order-btn')) return;

            const parentColumn: HTMLElement = target.closest('th');

            const urlParams = new URLSearchParams(window.location.search);
            const userId = urlParams.get('userid');
            const page = urlParams.get('page') || '1';

            const col = parentColumn.dataset.colName;
            const order = parentColumn.dataset.colOrder === 'asc' ? 'desc' : 'asc';

            let newUri = `${window.location.origin}${window.location.pathname}?userid=${userId}&page=${page}&col=${col}&ord=${order}`;

            const past = document.querySelector('[data-past-bookings]');
            if (pastCheck.checked) {
                newUri += '&past=true';
            }

            window.location.href = newUri;
        }


        function onCheckedPastBookings(event: Event): void {
            const target: HTMLInputElement = event.target;

            if (target.id !== 'pastBookings'
              && target.id !== 'checkForPastBookings') return;

            const urlParams = new URLSearchParams(window.location.search);
            const userId = urlParams.get('userid');
            const page = urlParams.get('page') || '1';
            const col = urlParams.get('col') || 'resource';
            const order = urlParams.get('ord') || 'asc';

            let newUri = `${window.location.origin}${window.location.pathname}?userid=${userId}&page=${page}&col=${col}&ord=${order}`;

            if (target.checked) {
                newUri += '&past=true';
            }

            window.location.href = newUri;
        }


        function onShowDeleteConfirmationModal(event): void {
            const button: HTMLElement = event.relatedTarget;
            const confirmationNumber = button.getAttribute('data-bs-confirmation');
            const bookingId = button.getAttribute('data-bs-booking-id');
            const confirmationLabel = document.getElementById('data-bs-confirmation');
            const deleteConfirmationBtn = document.getElementById('deleteConfirmationBtn');
            const hiddenInput = document.getElementById('delete_booking_bookingId');
            const deleteForm: HTMLFormElement = hiddenInput.closest('form');

            deleteConfirmationBtn.setAttribute('data-booking-id', bookingId);
            confirmationLabel.textContent = confirmationNumber;
            hiddenInput.value = bookingId;
            deleteForm.setAttribute('action', `/booking/${bookingId}/delete`);
        }


        function onClickEditBooking(event: Event): void {
            const target: HTMLElement = event.target;
            if (!target.classList.contains('edit-btn')) return;
            event.stopPropagation();
            const bookingId = target.getAttribute('data-bs-booking-id');
            window.location.href = window.location.origin + '/planning/booking/edit/' + bookingId;
        }
    }


    userSelect.addEventListener('change', onUserSelectChange);


    function onUserSelectChange(event: Event): void {
        const target: HTMLElement = event.target;

        if (target.id !== 'userSelect') return;

        const urlParams = new URLSearchParams(window.location.search);
        const userId = target.value;

        let newUri = `${window.location.origin}/planning/${userId}`;

        window.location.href = newUri;
    }
});

const overviewPage = (function () : void {
    const {appState} = require("./components/seatmap/seatmap.js");

    const {extractDateFromDateIsoString} = require("./components/seatmap/utils/utils");


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
});

const loginPage = (function () : void {
    const {validatePassword, validateEmail} = require('./utils/validation');


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
});

const newBookingPage = (function() : void {
    const { period, sanitizeDisplayDate, checkIfDatesAreValid, checkIfPeriodsOverlap } = require('./utils/dateUtils');
    const { isAvailableAPIData, checkBookableAvailabilityBySelectedDatesRESTCall, setUnavailablePeriods } = require('./api/checkBookableAvailabilityBySelectedDatesRESTCall');


    const dateDisplayFormat = 'en-GB';

    const bookingFrom: HTMLFormElement = document.querySelector('form[name="booking"]');
    const availableBookables: HTMLElement = document.getElementById("booking_bookable");
    const fromDateDOMElement: HTMLElement = document.getElementById("booking_start_date");
    const toDateDOMElement: HTMLElement = document.getElementById("booking_end_date");
    const submitBtn: HTMLElement = document.getElementById("submitBtn");
    const errorArea: HTMLElement = document.getElementById("errorArea");
    const errorList: HTMLElement = document.getElementById("errorList");

    let selectedUnavailablePeriods: period[] = [];


    fromDateDOMElement.addEventListener("change", limitMinimunToDate);
    bookingFrom.addEventListener("change", checkFormSate);
    // submitBtn.addEventListener('click', checkAvailabilityBeforeSubmit);

    /**
     * Limits the minimum date of "To Date" not to be before "From Date"
     */
    function limitMinimunToDate(): void {
        const fromDate: Date = new Date(fromDateDOMElement.value);
        const toDate: Date = new Date(toDateDOMElement.value);
        if (fromDate > toDate) {
            toDateDOMElement.value = fromDateDOMElement.value;
        }
        toDateDOMElement.min = fromDateDOMElement.value;
    }


    /**
     * Submit button is only enabled if both dates are set
     */
    function checkFormSate(e: Event): void {

        if (e.target !== availableBookables
          && e.target !== fromDateDOMElement
          && e.target !== toDateDOMElement) {
            return;
        }

        e.stopPropagation();

        const isSelectionValid: boolean = checkIfDatesAreValid(
          fromDateDOMElement.value,
          toDateDOMElement.value,
          selectedUnavailablePeriods);

        if (isSelectionValid
          || e.target === availableBookables) {
            clearErrorMessages();
            clearInputErrors();
            selectedUnavailablePeriods = [];
            submitBtn.disabled = false;
        } else {
            showErrorsOnInputsIfAny();
            submitBtn.disabled = true;
        }
    }


    // /**
    //  * Checks if the selected dates are available before submitting the form
    //  */
    // async function checkAvailabilityBeforeSubmit(e: Event): void {
    //     e.preventDefault();
    //     const id: number = availableBookables.value;
    //     const from: Date = new Date(fromDateDOMElement.value);
    //     const to: Date = new Date(toDateDOMElement.value);
    //
    //     const isAvailable: isAvailableAPIData = await checkBookableAvailabilityBySelectedDatesRESTCall(id, from, to);
    //
    //     if (isAvailable['isAvailable']) {
    //         return bookingFrom.submit();
    //     }
    //
    //     showBookingsOrUnavailableDates(isAvailable);
    //     selectedUnavailablePeriods = setUnavailablePeriods(isAvailable);
    //
    //     showErrorsOnInputsIfAny();
    // }


    /**
     * Displays unavailable dates and bookings to the user, if any
     * @param isAvailable
     */
    function showBookingsOrUnavailableDates(isAvailable: isAvailableAPIData): void {
        errorArea.classList.remove('error-display');
        errorArea.classList.add('error-display__show');
        errorList.innerHTML = '';

        isAvailable.bookings.forEach(booking => {
            errorList.innerHTML += `<li>${booking.bookableCode} booked from ${sanitizeDisplayDate(booking.from)} to ${sanitizeDisplayDate(booking.to)}</li>`;
        });

        isAvailable.unavailableDates.forEach(unavailableDate => {
            errorList.innerHTML += `<li>${unavailableDate.bookableCode} unavailable from ${sanitizeDisplayDate(unavailableDate.from)} to ${sanitizeDisplayDate(unavailableDate.to)}: ${unavailableDate.notes}</li>`;
        });
    }


    /**
     * Clears the error messages setted by showBookingsOrUnavailableDates()
     */
    function clearErrorMessages(): void {
        errorArea.classList.add('error-display');
        errorArea.classList.remove('error-display__show');
        errorList.innerHTML = '';
    }


    /**
     * Shows the users when the selected dates are overlaping with existing
     * bookings or unavalilable_dates
     */
    function showErrorsOnInputsIfAny() {
        clearInputErrors();

        const selectedPeriod: period = {
            from: new Date(fromDateDOMElement.value),
            to: new Date(toDateDOMElement.value),
        };
        const isSelectedPeriodOverlapping: boolean = selectedUnavailablePeriods.reduce((acc, period) => {
            return acc || checkIfPeriodsOverlap(selectedPeriod, period);
        }, false);

        if (isSelectedPeriodOverlapping) {
            fromDateDOMElement.classList.add('is-invalid');
            toDateDOMElement.classList.add('is-invalid');
        }
    }


    /**
     * Clears the errors applied to the input-date elements
     */
    function clearInputErrors() {
        fromDateDOMElement.classList.remove('is-invalid');
        toDateDOMElement.classList.remove('is-invalid');
    }
});

const editBookingPage = (function () : void {
    const { period, sanitizeDisplayDate, checkIfDatesAreValid, checkIfPeriodsOverlap } = require('./utils/dateUtils');
    const { isAvailableAPIData, isAvailableAPIDataOptions, checkBookableAvailabilityBySelectedDatesRESTCall, setUnavailablePeriods } = require('./api/checkBookableAvailabilityBySelectedDatesRESTCall');


    const bookingFrom: HTMLFormElement = document.getElementById("editBookingForm");
    const availableBookables: HTMLElement = document.getElementById("allBookables");
    const fromDateDOMElement: HTMLElement = document.getElementById("fromDate");
    const toDateDOMElement: HTMLElement = document.getElementById("toDate");
    const submitBtn: HTMLElement = document.getElementById("submitBtn");
    const errorArea: HTMLElement = document.getElementById("errorArea");
    const errorList: HTMLElement = document.getElementById("errorList");

    const bookingId: HTMLInputElement = document.getElementById('bookingId');

    let selectedUnavailablePeriods: period[] = [];


    fromDateDOMElement.addEventListener("change", limitMinimunToDate);
    bookingFrom.addEventListener("change", checkFormSate);
    submitBtn.addEventListener('click', checkAvailabilityBeforeSubmit);


    /**
     * Limits the minimum date of "To Date" not to be earlier than "From Date"
     */
    function limitMinimunToDate(): void {
        const fromDate: Date = new Date(fromDateDOMElement.value);
        const toDate: Date = new Date(toDateDOMElement.value);
        if (fromDate > toDate) {
            toDateDOMElement.value = fromDateDOMElement.value;
        }
        toDateDOMElement.min = fromDateDOMElement.value;
    }


    /**
     * Submit button is only enabled if both dates are set
     */
    function checkFormSate(e: Event): void {

        if (e.target !== availableBookables
          && e.target !== fromDateDOMElement
          && e.target !== toDateDOMElement) {
            return;
        }

        e.stopPropagation();

        const isSelectionValid: boolean = checkIfDatesAreValid(
          fromDateDOMElement.value,
          toDateDOMElement.value,
          selectedUnavailablePeriods);

        if (isSelectionValid
          || e.target === availableBookables) {
            clearErrorMessages();
            clearInputErrors();
            selectedUnavailablePeriods = [];
            submitBtn.disabled = false;
        } else {
            showErrorsOnInputsIfAny();
            submitBtn.disabled = true;
        }
    }


    /**
     * Checks if the selected dates are available before submitting the form
     */
    async function checkAvailabilityBeforeSubmit(e: Event): void {
        e.preventDefault();
        const id: number = availableBookables.value;
        const from: Date = new Date(fromDateDOMElement.value);
        const to: Date = new Date(toDateDOMElement.value);

        const options: isAvailableAPIDataOptions = {
            ignoreSelectedBooking: true,
            ignoreSelectedBookingId: bookingId.value,
        }

        const isAvailable: isAvailableAPIData = await checkBookableAvailabilityBySelectedDatesRESTCall(id, from, to, options);

        if (isAvailable['isAvailable']) {
            return bookingFrom.submit();
        }

        showBookingsOrUnavailableDates(isAvailable);
        selectedUnavailablePeriods = setUnavailablePeriods(isAvailable);

        showErrorsOnInputsIfAny();
    }


    /**
     * Displays unavailable dates and bookings to the user, if any
     * @param isAvailable
     */
    function showBookingsOrUnavailableDates(isAvailable: isAvailableAPIData): void {
        errorArea.classList.remove('error-display');
        errorArea.classList.add('error-display__show');
        errorList.innerHTML = '';

        isAvailable.bookings.forEach(booking => {
            errorList.innerHTML += `<li>${booking.bookableCode} booked from ${sanitizeDisplayDate(booking.from)} to ${sanitizeDisplayDate(booking.to)}</li>`;
        });

        isAvailable.unavailableDates.forEach(unavailableDate => {
            errorList.innerHTML += `<li>${unavailableDate.bookableCode} unavailable from ${sanitizeDisplayDate(unavailableDate.from)} to ${sanitizeDisplayDate(unavailableDate.to)}: ${unavailableDate.notes}</li>`;
        });
    }


    /**
     * Clears the error messages setted by showBookingsOrUnavailableDates()
     */
    function clearErrorMessages(): void {
        errorArea.classList.add('error-display');
        errorArea.classList.remove('error-display__show');
        errorList.innerHTML = '';
    }


    /**
     * Shows the users when the selected dates are overlaping with existing
     * bookings or unavalilable_dates
     */
    function showErrorsOnInputsIfAny() {
        clearInputErrors();

        const selectedPeriod: period = {
            from: new Date(fromDateDOMElement.value),
            to: new Date(toDateDOMElement.value),
        };
        const isSelectedPeriodOverlapping: boolean = selectedUnavailablePeriods.reduce((acc, period) => {
            return acc || checkIfPeriodsOverlap(selectedPeriod, period);
        }, false);

        if (isSelectedPeriodOverlapping) {
            fromDateDOMElement.classList.add('is-invalid');
            toDateDOMElement.classList.add('is-invalid');
        }
    }


    /**
     * Clears the errors applied to the input-date elements
     */
    function clearInputErrors() {
        fromDateDOMElement.classList.remove('is-invalid');
        toDateDOMElement.classList.remove('is-invalid');
    }
});

const listBookingsPage = (function () : void {
    const table: HTMLElement = document.getElementById('bookingsTable');
    const selectedColumn: HTMLElement = document.querySelector('[data-col-selected="true"]');
    const iconSelected: HTMLElement = selectedColumn.querySelector('i');
    const pastCheck: HTMLInputElement = document.getElementById('pastBookings');

    const deleteConmfirmationModal = document.getElementById('deleteConfirmation')


    iconSelected.classList.remove('bi-arrow-down-up');
    if (selectedColumn.dataset.colOrder === 'asc') {
        iconSelected.classList.add('bi-arrow-down');
    } else {
        iconSelected.classList.add('bi-arrow-up');
    }


    table.addEventListener('click', orderTable);
    table.addEventListener('click', onClickEditBooking);
    pastCheck.addEventListener('change', onCheckedPastBookings);
    deleteConmfirmationModal.addEventListener('show.bs.modal', onShowDeleteConfirmationModal);


    function orderTable(event: Event): void {
        const target: HTMLElement = event.target;

        if (!target.classList.contains('bi')
          && !target.classList.contains('order-btn')) return;

        const parentColumn: HTMLElement = target.closest('th');

        const urlParams = new URLSearchParams(window.location.search);
        const page = urlParams.get('page') || '1';

        const col = parentColumn.dataset.colName;
        const order = parentColumn.dataset.colOrder === 'asc' ? 'desc' : 'asc';

        let newUri = `${window.location.origin}${window.location.pathname}?page=${page}&col=${col}&ord=${order}`;

        const past = document.querySelector('[data-past-bookings]');
        if (pastCheck.checked) {
            newUri += '&past=true';
        }

        window.location.href = newUri;
    }


    function onCheckedPastBookings(event: Event): void {
        const target: HTMLInputElement = event.target;

        if (target.id !== 'pastBookings'
          && target.id !== 'checkForPastBookings') return;

        const urlParams = new URLSearchParams(window.location.search);
        const page = urlParams.get('page') || '1';
        const col = urlParams.get('col') || 'resource';
        const order = urlParams.get('ord') || 'asc';

        let newUri = `${window.location.origin}${window.location.pathname}?page=${page}&col=${col}&ord=${order}`;

        if (target.checked) {
            newUri += '&past=true';
        }

        window.location.href = newUri;
    }


    function onShowDeleteConfirmationModal(event): void {
        const button: HTMLElement = event.relatedTarget;
        const confirmationNumber = button.getAttribute('data-bs-confirmation');
        const bookingId = button.getAttribute('data-bs-booking-id');
        const confirmationLabel = document.getElementById('data-bs-confirmation');
        const deleteConfirmationBtn = document.getElementById('deleteConfirmationBtn');
        const hiddenInput = document.getElementById('delete_booking_bookingId');
        const deleteForm: HTMLFormElement = hiddenInput.closest('form');

        deleteConfirmationBtn.setAttribute('data-booking-id', bookingId);
        confirmationLabel.textContent = confirmationNumber;
        hiddenInput.value = bookingId;
        deleteForm.setAttribute('action', `/booking/${bookingId}/delete`);
    }


    function onClickEditBooking(event: Event): void {
        const target: HTMLElement = event.target;
        if (!target.classList.contains('edit-btn')) return;

        event.stopPropagation();

        const bookingId = target.getAttribute('data-bs-booking-id');

        window.location.href = window.location.origin + `/booking/${bookingId}/edit`;
    }
});

const manageBookablesPage = (function () : void {
    const table: HTMLElement = document.getElementById('unavailableDatesTable');
    const selectedColumn: HTMLElement = document.querySelector('[data-col-selected="true"]');
    const iconSelected: HTMLElement = selectedColumn.querySelector('i');
    const pastCheck: HTMLInputElement = document.getElementById('pastUnavailabledates');

    const deleteConmfirmationModal = document.getElementById('deleteConfirmation')


    iconSelected.classList.remove('bi-arrow-down-up');
    if (selectedColumn.dataset.colOrder === 'asc') {
        iconSelected.classList.add('bi-arrow-down');
    } else {
        iconSelected.classList.add('bi-arrow-up');
    }


    table.addEventListener('click', orderTable);
    table.addEventListener('click', onClickEditUnavailableDate);
    pastCheck.addEventListener('change', onCheckedPastUnavailableDates);
    deleteConmfirmationModal.addEventListener('show.bs.modal', onShowDeleteConfirmationModal);

    /**
     * Orders the table by the selected column, requesting the server to get the new
     * rendered page with the new ordered table
     *
     * @param event
     */
    function orderTable(event: Event): void {
        const target: HTMLElement = event.target;

        if (!target.classList.contains('bi')
          && !target.classList.contains('order-btn')) return;

        const parentColumn: HTMLElement = target.closest('th');

        const urlParams = new URLSearchParams(window.location.search);
        const page = urlParams.get('page') || '1';

        const col = parentColumn.dataset.colName;
        const order = parentColumn.dataset.colOrder === 'asc' ? 'desc' : 'asc';

        let newUri = `${window.location.origin}${window.location.pathname}?page=${page}&col=${col}&ord=${order}`;

        if (pastCheck.checked) {
            newUri += '&past=true';
        }

        window.location.href = newUri;
    }

    /**
     * Request the server to get a new rendered page with or without the past bookings,
     * according to the checkbox state, also maintaining the current column order
     *
     * @param event
     */
    function onCheckedPastUnavailableDates(event: Event): void {
        const target: HTMLInputElement = event.target;

        if (target.id !== 'pastUnavailabledates') return;

        const urlParams = new URLSearchParams(window.location.search);
        const page = urlParams.get('page') || '1';
        const col = urlParams.get('col') || 'bookable';
        const order = urlParams.get('ord') || 'asc';

        let newUri = `${window.location.origin}${window.location.pathname}?page=${page}&col=${col}&ord=${order}`;

        if (target.checked) {
            newUri += '&past=true';
        }

        window.location.href = newUri;
    }

    /**
     * Gets the unavailable date id from the button that triggered the show modal
     * event and sets it to the hidden input of the modals form and also the info label
     *
     * @param event
     */
    function onShowDeleteConfirmationModal(event): void {
        const button: HTMLElement = event.relatedTarget;
        const unavailableId = button.getAttribute('data-bs-booking-id');
        const confirmationIdLabel = document.getElementById('data-bs-confirmation');
        const hiddenInput = document.getElementById('unavailableId');

        confirmationIdLabel.textContent = unavailableId;
        hiddenInput.value = unavailableId;
    }

    /**
     * Handles the click event on the edit button of the table, getting the unavailable
     * date id from the button and redirecting to the edit page
     *
     * @param event
     */
    function onClickEditUnavailableDate(event: Event): void {
        const target: HTMLElement = event.target;
        if (!target.classList.contains('edit-btn')) return;

        event.stopPropagation();

        const unavailableDateId = target.getAttribute('data-bs-booking-id');

        window.location.href = window.location.origin + `/admin/unavailableDates/${unavailableDateId}/edit`;
    }

});

const createUserPage = (function () : void {
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
});

const userManagementPage = (function () : void {
    const table: HTMLElement = document.getElementById('usersTable');
    const selectedColumn: HTMLElement = document.querySelector('[data-col-selected="true"]');
    const iconSelected: HTMLElement = selectedColumn.querySelector('i');

    const deleteConmfirmationModal = document.getElementById('deleteConfirmation')


    iconSelected.classList.remove('bi-arrow-down-up');
    if (selectedColumn.dataset.colOrder === 'asc') {
        iconSelected.classList.add('bi-arrow-down');
    } else {
        iconSelected.classList.add('bi-arrow-up');
    }


    table.addEventListener('click', orderTable);
    table.addEventListener('click', handleEditUser);
    deleteConmfirmationModal.addEventListener('show.bs.modal', onShowDeleteConfirmationModal);


    /**
     * Orders the table by the selected column, requesting the server to get the new
     * rendered page with the new ordered table
     *
     * @param event
     */
    function orderTable(event: Event): void {
        const target: HTMLElement = event.target;

        if (!target.classList.contains('bi')
          && !target.classList.contains('order-btn')) return;

        const parentColumn: HTMLElement = target.closest('th');

        const urlParams = new URLSearchParams(window.location.search);
        const page = urlParams.get('page') || '1';

        const col = parentColumn.dataset.colName;
        const order = parentColumn.dataset.colOrder === 'asc' ? 'desc' : 'asc';

        window.location.href = `${window.location.origin}${window.location.pathname}?page=${page}&col=${col}&ord=${order}`;
    }


    /**
     * Gets the unavailable date id from the button that triggered the show modal
     * event and sets it to the hidden input of the modals form and also the info label
     *
     * @param event
     */
    function onShowDeleteConfirmationModal(event): void {
        const button: HTMLElement = event.relatedTarget;
        const userId = button.getAttribute('data-user-id');
        const userEmail = button.getAttribute('data-user-email');
        const confirmationIdLabel = document.getElementById('data-confirmation');
        const tr = button.closest('tr');

        const deleteAction = tr.getAttribute('data-delete-action');
        const deleteForm = document.getElementById('deleteUserForm');
        const hiddenInput = document.getElementById('delete_user_form_id');

        confirmationIdLabel.textContent = userEmail;
        hiddenInput.value = userId;

        deleteForm.action = deleteAction;
    }


    function handleEditUser(event: Event) {
        const target: HTMLElement = event.target;

        if (!target.classList.contains('editUserBtn')) return;

        window.location.href = `${window.location.origin}${target.getAttribute('data-edit-user-path')}`;
    }
});

const editUnavailableDatePage = (function () : void {
    const { period, sanitizeDisplayDate, checkIfDatesAreValid, checkIfPeriodsOverlap } = require('./utils/dateUtils');
    const { checkUnavailableDatesAvailabilityAPIData, setUnavailablePeriods, checkUnavailableDatesAvailabilityAPIDataOptions, checkUnavailableDatesAvailabilityBySelectedDatesRESTCall } = require('./api/checkUnavailableDatesAvailabilityBySelectedDatesRESTCall');


    const dateDisplayFormat = 'en-GB';


    const unavailableDatesFrom: HTMLFormElement = document.getElementById("newUnavailableDateForm");
    const availableBookables: HTMLElement = document.getElementById("allBookables");
    const unavailableDateId: HTMLInputElement = document.getElementById("unavailableDateId");
    const fromDateDOMElement: HTMLElement = document.getElementById("fromDate");
    const toDateDOMElement: HTMLElement = document.getElementById("toDate");
    const submitBtn: HTMLElement = document.getElementById("submitBtn");
    const errorArea: HTMLElement = document.getElementById("errorArea");
    const errorList: HTMLElement = document.getElementById("errorList");

    let selectedUnavailablePeriods: period[] = [];


    fromDateDOMElement.addEventListener("change", limitMinimunToDate);
    unavailableDatesFrom.addEventListener("change", checkFormSate);
    submitBtn.addEventListener('click', checkAvailabilityBeforeSubmit);

    /**
     * Limits the minimum date of "To Date" not to be before "From Date"
     */
    function limitMinimunToDate(): void {
        const fromDate: Date = new Date(fromDateDOMElement.value);
        const toDate: Date = new Date(toDateDOMElement.value);
        if (fromDate > toDate) {
            toDateDOMElement.value = fromDateDOMElement.value;
        }
        toDateDOMElement.min = fromDateDOMElement.value;
    }


    /**
     * Submit button is only enabled if both dates are set
     */
    function checkFormSate(e: Event): void {

        if (e.target !== availableBookables
          && e.target !== fromDateDOMElement
          && e.target !== toDateDOMElement) {
            return;
        }

        e.stopPropagation();

        const isSelectionValid: boolean = checkIfDatesAreValid(
          fromDateDOMElement.value,
          toDateDOMElement.value,
          selectedUnavailablePeriods);

        if (isSelectionValid
          || e.target === availableBookables) {
            clearErrorMessages();
            clearInputErrors();
            selectedUnavailablePeriods = [];
            submitBtn.disabled = false;
        } else {
            showErrorsOnInputsIfAny();
            submitBtn.disabled = true;
        }
    }


    /**
     * Checks if the selected dates are available before submitting the form
     */
    async function checkAvailabilityBeforeSubmit(e: Event): void {
        e.preventDefault();
        const availableDateId: number = unavailableDateId.value;
        const bookableId: number = availableBookables.value;
        const from: Date = new Date(fromDateDOMElement.value);
        const to: Date = new Date(toDateDOMElement.value);

        const isAvailable: checkUnavailableDatesAvailabilityAPIData = await checkUnavailableDatesAvailabilityBySelectedDatesRESTCall(bookableId, from, to, {
            ignoreSelectedUnavailableDate: true,
            ignoreSelectedUnavailableDateId: availableDateId
        });

        if (isAvailable['isAvailable']) {
            return unavailableDatesFrom.submit();
        }

        showBookingsOrUnavailableDates(isAvailable);
        selectedUnavailablePeriods = setUnavailablePeriods(isAvailable);

        showErrorsOnInputsIfAny();
    }


    /**
     * Displays unavailable dates and bookings to the user, if any
     * @param isAvailable
     */
    function showBookingsOrUnavailableDates(isAvailable: checkUnavailableDatesAvailabilityAPIData): void {
        errorArea.classList.remove('error-display');
        errorArea.classList.add('error-display__show');
        errorList.innerHTML = '';

        isAvailable.unavailableDates.forEach(unavailableDate => {
            errorList.innerHTML += `<li>${unavailableDate.bookableCode} unavailable from ${sanitizeDisplayDate(unavailableDate.from)} to ${sanitizeDisplayDate(unavailableDate.to)}: ${unavailableDate.notes}</li>`;
        });
    }


    /**
     * Clears the error messages setted by showBookingsOrUnavailableDates()
     */
    function clearErrorMessages(): void {
        errorArea.classList.add('error-display');
        errorArea.classList.remove('error-display__show');
        errorList.innerHTML = '';
    }


    /**
     * Shows the users when the selected dates are overlaping with existing
     * bookings or unavalilable_dates
     */
    function showErrorsOnInputsIfAny() {
        clearInputErrors();

        const selectedPeriod: period = {
            from: new Date(fromDateDOMElement.value),
            to: new Date(toDateDOMElement.value),
        };
        const isSelectedPeriodOverlapping: boolean = selectedUnavailablePeriods.reduce((acc, period) => {
            return acc || checkIfPeriodsOverlap(selectedPeriod, period);
        }, false);

        if (isSelectedPeriodOverlapping) {
            fromDateDOMElement.classList.add('is-invalid');
            toDateDOMElement.classList.add('is-invalid');
        }
    }


    /**
     * Clears the errors applied to the input-date elements
     */
    function clearInputErrors() {
        fromDateDOMElement.classList.remove('is-invalid');
        toDateDOMElement.classList.remove('is-invalid');
    }
});

const newUnavailableDate = (function () : void {
    const { period, sanitizeDisplayDate, checkIfDatesAreValid, checkIfPeriodsOverlap } = require('./utils/dateUtils');
    const { checkUnavailableDatesAvailabilityAPIData, setUnavailablePeriods, checkUnavailableDatesAvailabilityAPIDataOptions, checkUnavailableDatesAvailabilityBySelectedDatesRESTCall } = require('./api/checkUnavailableDatesAvailabilityBySelectedDatesRESTCall');


    const dateDisplayFormat = 'en-GB';


    const unavailableDatesFrom: HTMLFormElement = document.getElementById("newUnavailableDateForm");
    const availableBookables: HTMLElement = document.getElementById("allBookables");
    const fromDateDOMElement: HTMLElement = document.getElementById("fromDate");
    const toDateDOMElement: HTMLElement = document.getElementById("toDate");
    const submitBtn: HTMLElement = document.getElementById("submitBtn");
    const errorArea: HTMLElement = document.getElementById("errorArea");
    const errorList: HTMLElement = document.getElementById("errorList");

    let selectedUnavailablePeriods: period[] = [];


    fromDateDOMElement.addEventListener("change", limitMinimunToDate);
    unavailableDatesFrom.addEventListener("change", checkFormSate);
    submitBtn.addEventListener('click', checkAvailabilityBeforeSubmit);

    /**
     * Limits the minimum date of "To Date" not to be before "From Date"
     */
    function limitMinimunToDate(): void {
        const fromDate: Date = new Date(fromDateDOMElement.value);
        const toDate: Date = new Date(toDateDOMElement.value);
        if (fromDate > toDate) {
            toDateDOMElement.value = fromDateDOMElement.value;
        }
        toDateDOMElement.min = fromDateDOMElement.value;
    }


    /**
     * Submit button is only enabled if both dates are set
     */
    function checkFormSate(e: Event): void {

        if (e.target !== availableBookables
          && e.target !== fromDateDOMElement
          && e.target !== toDateDOMElement) {
            return;
        }

        e.stopPropagation();

        const isSelectionValid: boolean = checkIfDatesAreValid(
          fromDateDOMElement.value,
          toDateDOMElement.value,
          selectedUnavailablePeriods);

        if (isSelectionValid
          || e.target === availableBookables) {
            clearErrorMessages();
            clearInputErrors();
            selectedUnavailablePeriods = [];
            submitBtn.disabled = false;
        } else {
            showErrorsOnInputsIfAny();
            submitBtn.disabled = true;
        }
    }


    /**
     * Checks if the selected dates are available before submitting the form
     */
    async function checkAvailabilityBeforeSubmit(e: Event): void {
        e.preventDefault();
        const id: number = availableBookables.value;
        const from: Date = new Date(fromDateDOMElement.value);
        const to: Date = new Date(toDateDOMElement.value);

        const isAvailable: checkUnavailableDatesAvailabilityAPIData = await checkUnavailableDatesAvailabilityBySelectedDatesRESTCall(id, from, to);

        if (isAvailable['isAvailable']) {
            return unavailableDatesFrom.submit();
        }

        showBookingsOrUnavailableDates(isAvailable);
        selectedUnavailablePeriods = setUnavailablePeriods(isAvailable);

        showErrorsOnInputsIfAny();
    }


    /**
     * Displays unavailable dates and bookings to the user, if any
     * @param isAvailable
     */
    function showBookingsOrUnavailableDates(isAvailable: checkUnavailableDatesAvailabilityAPIData): void {
        errorArea.classList.remove('error-display');
        errorArea.classList.add('error-display__show');
        errorList.innerHTML = '';

        isAvailable.unavailableDates.forEach(unavailableDate => {
            errorList.innerHTML += `<li>${unavailableDate.bookableCode} unavailable from ${sanitizeDisplayDate(unavailableDate.from)} to ${sanitizeDisplayDate(unavailableDate.to)}: ${unavailableDate.notes}</li>`;
        });
    }


    /**
     * Clears the error messages setted by showBookingsOrUnavailableDates()
     */
    function clearErrorMessages(): void {
        errorArea.classList.add('error-display');
        errorArea.classList.remove('error-display__show');
        errorList.innerHTML = '';
    }


    /**
     * Shows the users when the selected dates are overlaping with existing
     * bookings or unavalilable_dates
     */
    function showErrorsOnInputsIfAny() {
        clearInputErrors();

        const selectedPeriod: period = {
            from: new Date(fromDateDOMElement.value),
            to: new Date(toDateDOMElement.value),
        };
        const isSelectedPeriodOverlapping: boolean = selectedUnavailablePeriods.reduce((acc, period) => {
            return acc || checkIfPeriodsOverlap(selectedPeriod, period);
        }, false);

        if (isSelectedPeriodOverlapping) {
            fromDateDOMElement.classList.add('is-invalid');
            toDateDOMElement.classList.add('is-invalid');
        }
    }


    /**
     * Clears the errors applied to the input-date elements
     */
    function clearInputErrors() {
        fromDateDOMElement.classList.remove('is-invalid');
        toDateDOMElement.classList.remove('is-invalid');
    }
});

const currentPage = window.location.pathname;

const planningPageRegex = /\/planning\/(\d+)/;
const editBookingRegex = /\/booking\/(\d+)\/edit/;
const editBookingPlaningRegex = /\/planning\/booking\/edit\/(\d+)/;
const editAdminUsersRegex = /\/admin\/users\/edit\/(\d+)/;
const editUnavailableDateRegex = /\/admin\/unavailableDates\/(\d+)\/edit/;

if (currentPage === '/planning') {
    selectUserPage();
} else if (planningPageRegex.test(currentPage)) {
    planningPage();
} else if (currentPage === '/') {
    overviewPage();
} else if (currentPage === '/login') {
    loginPage();
} else if (currentPage === '/booking/new' || currentPage === '/planning/booking/new/create') {
    newBookingPage();
} else if (editBookingRegex.test(currentPage) || editBookingPlaningRegex.test(currentPage)) {
    editBookingPage();
} else if (currentPage === '/booking') {
    listBookingsPage();
} else if (currentPage === '/admin/bookable') {
    manageBookablesPage();
} else if (currentPage === '/admin/users/create' || editAdminUsersRegex.test(currentPage)) {
    createUserPage();
} else if (currentPage === '/admin/users') {
    userManagementPage();
} else if (editUnavailableDateRegex.test(currentPage)) {
    editUnavailableDatePage();
} else if (currentPage === '/admin/unavailableDates/new/create') {
    newUnavailableDate();
}
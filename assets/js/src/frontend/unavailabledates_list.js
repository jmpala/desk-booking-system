"use strict";

const currentPage = window.location.pathname;

(function () {
    if (currentPage !== '/admin/unavailableDates') {
        return;
    }

    console.log('unavailableDates.js loaded');

    const deleteForm: HTMLFormElement = document.querySelector('form[name="delete_unavailable_dates"]');
    const deleteModal: HTMLElement = document.querySelector('#deleteConfirmationModal');
    const idToDeleteLabel: HTMLElement = document.querySelector('#idToDeleteLabel');

    deleteModal.addEventListener('show.bs.modal',prepareFormAction)

    function prepareFormAction(event) {
        const unavailableDatesId = event.relatedTarget.dataset.bsBookingId;
        idToDeleteLabel.innerText = unavailableDatesId;
        deleteForm.action = `/admin/unavailableDates/${unavailableDatesId}/delete`;
    }
})();

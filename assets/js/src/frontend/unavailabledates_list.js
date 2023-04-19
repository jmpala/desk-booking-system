"use strict";

const currentPage = window.location.pathname;

(function () {
    if (currentPage !== '/admin/unavailableDates') {
        return;
    }

    console.log('unavailableDates.js loaded');

    const table = document.querySelector('#unavailableDatesTable');
    const deleteForm: HTMLFormElement = document.querySelector('form[name="delete_unavailable_dates"]');
    const deleteModal: HTMLElement = document.querySelector('#deleteConfirmationModal');
    const idToDeleteLabel: HTMLElement = document.querySelector('#idToDeleteLabel');

    table.addEventListener('click', editButtonClick);
    deleteModal.addEventListener('show.bs.modal', prepareFormAction);

    function editButtonClick(event) {
        if (!event.target.classList.contains('edit-btn')) {
            return;
        }
        const unavailableDatesId = event.target.dataset.bsBookingId;
        window.location.href = `/admin/unavailableDates/${unavailableDatesId}/edit`;
    }

    function prepareFormAction(event) {
        const unavailableDatesId = event.relatedTarget.dataset.bsBookingId;
        idToDeleteLabel.innerText = unavailableDatesId;
        deleteForm.action = `/admin/unavailableDates/${unavailableDatesId}/delete`;
    }
})();

"use strict";

const currentPage = window.location.pathname;

(function (): void {
  if (currentPage !== '/admin/unavailableDates') return;

  console.log('admin_unavailabledates_list.js loaded');

  const table = document.querySelector('#unavailableDatesTable');
  const selectedColumn: HTMLElement = document.querySelector('[data-col-selected="true"]');
  const iconSelected: HTMLElement = selectedColumn.querySelector('i');
  const pastCheck: HTMLInputElement = document.getElementById('pastUnavailabledates');

  const deleteForm: HTMLFormElement = document.querySelector('form[name="delete_unavailable_dates"]');
  const deleteModal: HTMLElement = document.querySelector('#deleteConfirmationModal');
  const idToDeleteLabel: HTMLElement = document.querySelector('#idToDeleteLabel');

  iconSelected.classList.remove('bi-arrow-down-up');
  if (selectedColumn.dataset.colOrder === 'asc') {
    iconSelected.classList.add('bi-arrow-down');
  } else {
    iconSelected.classList.add('bi-arrow-up');
  }

  table.addEventListener('click', orderTable);
  table.addEventListener('click', editButtonClick);
  pastCheck.addEventListener('change', onCheckedPastBookings);
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

    if (target.id !== 'pastUnavailabledates'
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

})();

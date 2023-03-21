"use strict";


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
  const userId = urlParams.get('userid');
  const page = urlParams.get('page') || '1';

  const col = parentColumn.dataset.colName;
  const order = parentColumn.dataset.colOrder === 'asc' ? 'desc' : 'asc';

  let newUri = `${window.location.origin}${window.location.pathname}?userid=${userId}&page=${page}&col=${col}&ord=${order}`;

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
  const userId = urlParams.get('userid');
  const page = urlParams.get('page') || '1';
  const col = urlParams.get('col') || 'bookable';
  const order = urlParams.get('ord') || 'asc';

  let newUri = `${window.location.origin}${window.location.pathname}?userid=${userId}&page=${page}&col=${col}&ord=${order}`;

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

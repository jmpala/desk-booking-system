"use strict";

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
  const hiddenInput = document.getElementById('bookingId');

  deleteConfirmationBtn.setAttribute('data-booking-id', bookingId);
  confirmationLabel.textContent = confirmationNumber;
  hiddenInput.value = bookingId;
}


function onClickEditBooking(event: Event): void {
  const target: HTMLElement = event.target;
  if (!target.classList.contains('edit-btn')) return;

  event.stopPropagation();

  const bookingId = target.getAttribute('data-bs-booking-id');

  window.location.href = window.location.origin + '/booking/edit/' + bookingId;
}
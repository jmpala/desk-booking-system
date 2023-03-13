"use strict";

const table: HTMLElement = document.querySelector('.table');
const selectedColumn: HTMLElement = document.querySelector('[data-col-selected="true"]');
const iconSelected: HTMLElement = selectedColumn.querySelector('i');
const pastCheck: HTMLInputElement = document.getElementById('pastBookings');

iconSelected.classList.remove('bi-arrow-down-up');
if (selectedColumn.dataset.colOrder === 'asc') {
  iconSelected.classList.add('bi-arrow-down');
} else {
  iconSelected.classList.add('bi-arrow-up');
}


table.addEventListener('click', orderTable);
pastCheck.addEventListener('change', onCheckedPastBookings);


function orderTable(event: Event) {
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


function onCheckedPastBookings(event: Event) {
  const target: HTMLInputElement = event.target;

  if (target.id !== 'pastBookings') return;

  const urlParams = new URLSearchParams(window.location.search);
  const page = urlParams.get('page') || '1';
  const col = urlParams.get('col') || 'resource';
  const order = urlParams.get('ord') || 'asc';

  let newUri = `${window.location.origin}${window.location.pathname}?page=${page}&col=${col}&ord=${order}`;

  if (pastCheck.checked) {
    newUri += '&past=true';
  }

  window.location.href = newUri;
}

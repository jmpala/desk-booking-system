"use strict";

const currentPage = window.location.pathname;

(function (): void {
  if (currentPage !== '/admin/users') return;

  console.log('admin_users_list.js loaded');

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
})();
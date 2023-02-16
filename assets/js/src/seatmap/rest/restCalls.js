"use strict";

export async function getAllBookables() {
  return fetch("/api/booking")
      .then(response => response.json());
}
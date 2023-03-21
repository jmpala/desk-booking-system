"use strict";

import {period} from "../utils/dateUtils";

export async function checkUnavailableDatesAvailabilityBySelectedDatesRESTCall(unavailableDateId: number, from: Date, to: Date, options?: checkUnavailableDatesAvailabilityAPIDataOptions): checkUnavailableDatesAvailabilityAPIData {
// TODO: implement CSRF token
  const res: Response = await fetch(`/api/admin/unavailableDates/${unavailableDateId}`, {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify({
      from: from,
      to: to,
      ignoreSelectedUnavailableDate: options?.ignoreSelectedUnavailableDate ?? false,
      ignoreSelectedUnavailableDateId: options?.ignoreSelectedUnavailableDateId ?? null,
    })
  });

  return await res.json();
}


export function setUnavailablePeriods(isAvailable: checkUnavailableDatesAvailabilityAPIData): period[] {
  const selectedUnavailablePeriods: period[] = [];

  isAvailable.unavailableDates.forEach(unavailableDate => {
    selectedUnavailablePeriods.push({
      from: new Date(unavailableDate.from),
      to: new Date(unavailableDate.to),
    });
  });

  return selectedUnavailablePeriods;
}


export type checkUnavailableDatesAvailabilityAPIData = {
  isAvailable: boolean,
  unavailableDates: {
    id: number,
    from: Date,
    to: Date,
    notes: string,
    bookableCode: string,
  }[],
};


export type checkUnavailableDatesAvailabilityAPIDataOptions = {
  ignoreSelectedUnavailableDate: boolean,
  ignoreSelectedUnavailableDateId?: number
};
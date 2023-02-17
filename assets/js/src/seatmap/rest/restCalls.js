"use strict";

export async function getSeatmapStausByDate(date: Date): Array {
  const normalizedDate = (date ?? new Date()).toISOString().slice(0, 10);
  const res = await fetch(`/sketch/${normalizedDate}`);
  return await res.json();
}
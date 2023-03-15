export type period = {
  from: Date,
  to: Date,
}


/**
 * Creates a date string in the format defined in dateDisplayFormat
 *
 * @param date
 * @returns {string}
 */
export function sanitizeDisplayDate(date: string): string {
  return new Date(date).toLocaleDateString('en-GB');
}


/**
 * Checks if the dates are not empty and if they are not overlapping with any unavailable periods
 *
 * @param from
 * @param to
 * @param unavailablePerdiods
 * @returns {boolean}
 */
export function checkIfDatesAreValid(from: HTMLInputElement, to: HTMLInputElement, unavailablePerdiods: period[]): boolean {
  const isEmpty: boolean = from.value === '' || to.value === '';
  if (isEmpty) {
    return false;
  }

  const selectedPeriod: period = {
    from: new Date(from.value),
    to: new Date(to.value),
  };

  const isUnavailable: boolean = unavailablePerdiods.reduce((acc, period) => {
    return acc || checkIfPeriodsOverlap(selectedPeriod, period);
  }, false);
  if (isUnavailable) {
    return false;
  }

  return true;
}


/**
 * Checks if two periods are overlapping
 *
 * @param period1
 * @param period2
 * @returns {boolean}
 */
export function checkIfPeriodsOverlap(period1: period, period2: period): boolean {
  return period1.from <= period2.to && period1.to >= period2.from;
}
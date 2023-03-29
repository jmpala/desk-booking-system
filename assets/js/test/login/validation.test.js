'use strict';

import {validateEmail, validatePassword} from '../../src/utils/validation';
import {test, expect} from '@jest/globals';

test.each`
  email    | expected
  ${"valid@email.com"} | ${true}
  ${"valid_92@email.com"} | ${true}
  ${"valid+92_@email.com"} | ${true}
`('email is valid', ({email, expected}) => {
    expect(validateEmail(email)).toBe(expected);
});

test.each`
  email    | expected
  ${"invalid"} | ${false}
  ${"@invalid.com"} | ${false}
  ${"invalid@invalid"} | ${false}
  ${"invalid.com"} | ${false}
`('email is invalid', ({email, expected}) => {
    expect(validateEmail(email)).toBe(expected);
});

test.each`
  password    | expected
  ${"X@_-1xxx"} | ${true}
  ${"X@_-1xxxxxxxxxxxxxxx"} | ${true}
`('password is valid', ({password, expected}) => {
    expect(validatePassword(password)).toBe(expected);
});

test.each`
  password    | expected
  ${"X@_-1xx"} | ${false}
  ${"X@_-1xxxxxxxxxxxxxxxx"} | ${false}
  ${"X@_-xxxxxxxxxxxxxxxx"} | ${false}
  ${"Xxxx1xxxxxxxxxxxxxxx"} | ${false}
  ${"x@_-xxxxxxxxxxxxxxxx"} | ${false}
`('password is not valid', ({password, expected}) => {
    expect(validatePassword(password)).toBe(expected);
});
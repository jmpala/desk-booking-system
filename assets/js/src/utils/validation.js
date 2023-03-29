'use strict';

import {validate} from "email-validator";
import PasswordValidator from "password-validator";

// Email Validation
export function validateEmail(email: string) {
    return validate(email);
}

// Password validation
export function validatePassword(password: string) {
    let schema = new PasswordValidator();
    schema.is().min(8)
        .is().max(20)
        .has().uppercase()
        .has().lowercase()
        .has().symbols()
        .has().digits();
    return schema.validate(password);
}
import '../../sass/pages/main/main_login.scss';


// get element label & password
const emailInput = document.querySelector('#email');
const passwordInput = document.querySelector('#password');
const formBtn = document.querySelector('#submit-btn');

formBtn.addEventListener('click', submitLoginForm);

// Email Validation
function validateEmail(email) {
    if (!isEmailSemanticallyCorrect(email)) return false;

    // if(!isEmailSemanticallyCorrect(email)
    //     || !hasEmailValidDomain(email)) return false;

    return true;
}

function isEmailSemanticallyCorrect(email) {
    if(!email) return false;
    const validEmailRegex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
    return validEmailRegex.test(email);
}

function hasEmailValidDomain(email) {
    const [username , domain] = email.split('@');
    console.log(`usename: ${username} - domain: ${domain}`); // TODO: erase line

    // TODO: call to validation service simulation
    const validDomain = 'hotmail.com';

    return validDomain === domain;
}

// Password validation
function validatePassword(password) {
    if(!isPasswordSemanticallyCorrect(password)
        || !isPasswordLongerThanMinLength(password)) return false;
    return true;
}

function isPasswordSemanticallyCorrect(password) {
    if(!password) return false;
    const validPasswordRegex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/;
    return validPasswordRegex.test(password);
}

function isPasswordLongerThanMinLength(password) {
    const passwordMinLength = 8;
    return passwordMinLength <= password.length;
}

// Submit btn
function submitLoginForm(e) {


    console.log(`email: ${emailInput.value} - password: ${passwordInput.value}`);// TODO: erase line
    const email = emailInput.value;
    const password = passwordInput.value;

    if(!email
    || !password) {
        e.preventDefault();
        alert('Empty email or password');
        return;
    }

    if(!validateEmail(email)
    || !validatePassword(password)) {
        e.preventDefault();
        alert('Invalid email or password');
        return;
    }

}
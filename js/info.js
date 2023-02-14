const form_check = document.getElementById('form');
const email = document.getElementById('email');
const btn_submit = document.getElementById('submit');
const EMAIL_REGEXP = /^(([^<>()[\].,;:\s@"]+(\.[^<>()[\].,;:\s@"]+)*)|(".+"))@(([^<>()[\].,;:\s@"]+\.)+[^<>()[\].,;:\s@"]{2,})$/iu;

const checkFieldFill = (field) => {
    if(field.value.trim() === '') {
        field.classList.add('border-error');
    } else {
        field.classList.remove('border-error');
    }
};

const addFieldFillListeners = (field) => {
    field.addEventListener('input', () => {            
        checkFieldFill(field);
        if (field.id === 'email') {
            checkEmailCorrect(field);
        }
    });

    field.addEventListener('blur', () => {
        field.value = field.value.trim();
        checkFieldFill(field);
        if (field.id === 'email') {
            checkEmailCorrect(field);
        }
    });    
};

const checkEmailCorrect = (field) => {
    if (EMAIL_REGEXP.test(field.value)) {
        field.classList.remove('border-error');
    } else {
        field.classList.add('border-error');
    }
};

if (email) addFieldFillListeners(email);

if (btn_submit) btn_submit.addEventListener("click", (e) => {
    if(email.value.trim() === '') {
        checkFieldFill(email);
        e.preventDefault();
    }
});
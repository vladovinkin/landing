const form_check = document.getElementById('form');
const company = document.getElementById('company');
const phone = document.getElementById('phone');
const email = document.getElementById('email');
const brief = document.getElementById('text');
const msg_form = document.getElementById('form-message');
const btn_send_brief = document.getElementById('submit');
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
    
    field.addEventListener('focus', () => {
        msg_form.innerText = ' ';
    });
};

const checkEmailCorrect = (field) => {
    if (EMAIL_REGEXP.test(field.value)) {
        field.classList.remove('border-error');
    } else {
        field.classList.add('border-error');
    }
};

addFieldFillListeners(company);
addFieldFillListeners(phone);
addFieldFillListeners(email);
addFieldFillListeners(brief);

btn_send_brief.addEventListener("click", (e) => {
    e.preventDefault();

    fetch('/check.php', {
        method: 'POST',
        body: new FormData(document.getElementById('form'))
    }).then(
        response => {
            const result = response.json();
            return result;
        }
    ).then(
        result => {
            msg_form.innerHTML = result.text;
            
            if (result.err_code == 1) {
                company.classList.add('border-error');
            }
            if (result.err_code == 2) {
                phone.classList.add('border-error');
            }
            if (result.err_code == 3 || result.err_code == 4) {
                email.classList.add('border-error');
            }
            if (result.err_code == 5) {
                brief.classList.add('border-error');
            }
            if ([1,2,3,4,5].includes(result.err_code)) {
                msg_form.classList.remove('message-success');
                msg_form.classList.add('message-error');
            } else {
                msg_form.classList.remove('message-error');
                msg_form.classList.add('message-success');
                form.reset();
            }

        }
    );
})
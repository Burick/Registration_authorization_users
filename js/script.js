var valueForm = {
    'login': true,
    'pass': true,
    'date': false,
    'pass_repeat': true,
    'email': true

};

function validateForm(form) {

    var pattern = {
        'login': /^[a-zA-Z][a-zA-Z0-9-_\.]{1,20}$/, // буквы и цифры, не менее 2-х, первая буква
        'pass': /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s).*$/, // большие и маленнькие буквы и цифры ,
        'email': /^[0-9a-z]([\.-]?\w+)*@[0-9a-z]([\.-]?[0-9a-z])*(\.[0-9a-z]{2,4})+$/,
        'date': /(19|20)\d\d-((0[1-9]|1[012])-(0[1-9]|[12]\d)|(0[13-9]|1[012])-30|(0[13578]|1[02])-31)/ // дата в формате YYYY-MM-DD
    };
    var elements = form.elements;
    var req = true;
    for (var key in valueForm) {
        var status = true;
        var elementName = key;
        var element = elements[elementName];
        var elementValue = element.value.trim();
        var elementPattern = pattern[elementName];
        (elementPattern != undefined) ? status = elementPattern.test(elementValue) : status = true;

        if (valueForm[key] === true) {
            if (elementName == 'pass_repeat' && status == true && elementValue != document.getElementById('pass').value.trim()) {
                status = false;
            }
            if (status == false)
                req = false;

            setFieldStatus(element, status);
        } else {
            if (elementName == 'date' && elementValue != '') {
                setFieldStatus(element, status);
            }

        }
    }
    return req;
} // validateForm(form)

function setFieldStatus(element, status) {
    if (status) {
        element.parentElement.parentElement.className = 'form-group';
        element.previousSibling.firstElementChild.className = 'glyphicon glyphicon-ok';
        element.parentElement.parentElement.firstElementChild.nextSibling.className = 'example';
        return true;
    } else {
        element.parentElement.parentElement.className = 'form-group has-error';
        element.previousSibling.firstElementChild.className = 'glyphicon glyphicon-warning-sign';
        element.parentElement.parentElement.firstElementChild.nextSibling.className = 'text-danger';
        return false;
    }
} //setFieldStatus(field, status)    


function langTrigger(lang) {

    if (lang == 'EN') {
        var lang_in = 'RU';
        var lang_out = 'EN';
    } else {
        var lang_in = 'EN';
        var lang_out = 'RU';
    }
    var trigger = document.getElementById('lahg_trigger').getElementsByTagName('a');

    for (var key = trigger.length - 1; key >= 0; key--) {
        if (trigger.item(key).className == 'active') {
            trigger.item(key).className = '';
        } else {
            trigger.item(key).className = 'active';
        }

    };




    var translation = {
        'lang_link': {
            'RU': 'select English language',
            'EN': 'выбрать русский язык'
        },        
        'heading': {
            'RU': 'Для продолжения работы с сервисом необходимо зарегистрироваться или',
            'EN': 'To continue the work with the service you need to register or'
        },
        'heading_link': {
            'RU': 'войти в аккаунт',
            'EN': 'Sing in'
        },
        'legend': {
            'RU': 'Регистрация нового пользователя',
            'EN': 'New User Registration'

        },
        'alert': {
            'RU': 'поля обязательные для заполнения',
            'EN': 'fields are required'

        },
        'login_label': {
            'RU': 'Логин',
            'EN': 'Login'

        },
        'login_text': {
            'RU': 'логин может содержать большие и маленькие латинские буквы и цифры, начинаться обязательно с буквы',
            'EN': 'login may contain large and small letters and numbers, be sure to start with the letter'

        },

        'name_label': {
            'RU': 'Имя',
            'EN': 'Name'

        },
        'name_text': {
            'RU': 'имя может содержать большие и маленькие буквы латиницей и кириллицей',
            'EN': 'login may contain large and small latin and cyrilik letters'

        },
        'pass_label': {
            'RU': 'Пароль',
            'EN': 'Password'

        },
        'pass_text': {
            'RU': 'обязательно должны присутствовать большие и маленькие буквы латинского алфавита, а также цифры',
            'EN': 'must be present upper and lowercase letters of the Latin alphabet and digit'

        },
        'pass_repeat_label': {
            'RU': 'Повторите пароль',
            'EN': 'Repeat password'

        },
        'pass_repeat_text': {
            'RU': 'Пароли должны совпадать',
            'EN': 'Passwords must match'

        },        
        'date_label': {
            'RU': 'Дата рождения',
            'EN': 'Date of Birth'

        },
        'date_text': {
            'RU': 'в формате дд-мм-гггг',
            'EN': 'in the format dd-mm-yyyy'

        },
        'sex_label': {
            'RU': 'Пол',
            'EN': 'Sex'

        },
        'sex_text_male': {
            'RU': 'мужской',
            'EN': 'Male'

        },
        'sex_text_female': {
            'RU': 'женский',
            'EN': 'Female'

        },
        'upload_label': {
            'RU': 'Загрузить фото',
            'EN': 'Upload photo'

        },
        'upload_text': {
            'RU': 'файл *.jpg, *.gif, *.png - имя файла может содержать буквы латиницей, цифры и нижнее подчеркивание, максимальный размер',
            'EN': 'file *.jpg, *.gif, *.png - the file name may contain Latin letters, numbers, and underscores, maximum size'
        },
        'foter_button': {
            'RU': 'Отправить данные',
            'EN': 'Sing Up'
        },
        'footer_string1': {
            'RU': 'ИЛИ',
            'EN': 'OR'
        },                                                       
        'foter_link': {
            'RU': 'Войти в аккаунт',
            'EN': 'Sing In'
        },
        'placeholder_login': {
            'RU': 'Введите логин',
            'EN': 'Enter your login'
        },
        'placeholder_name': {
            'RU': 'Введите имя',
            'EN': 'Enter your name'
        },        
        'placeholder_pass': {
            'RU': 'Введите пароль',
            'EN': 'Enter password'
        },
        'placeholder_pass_repeat': {
            'RU': 'Повторите пароль',
            'EN': 'Password repeat'
        },
        'placeholder_e-mail': {
            'RU': 'Введите e-mail',
            'EN': 'Enter e-mail'
        }
    }; //translation

    for (var key in translation) {

        var str_in = translation[key][lang_in];
        var str_out = translation[key][lang_out];

        document.body.innerHTML = document.body.innerHTML.replace(str_in, str_out);

    };


}


/* поддержка метода trim в Ie 8 */

if(typeof String.prototype.trim !== 'function') {
  String.prototype.trim = function() {
    return this.replace(/^\s+|\s+$/g, '');
  }
}

/* поддержка свойства firstElementChild в Ie 8 */

if (document.documentElement.firstElementChild === undefined) { // (1)

  Object.defineProperty(Element.prototype, 'firstElementChild', { // (2)
    get: function() {
      var el = this.firstChild;
      do {
        if (el.nodeType === 1) {
          return el;
        }
        el = el.nextSibling;
      } while (el);

      return null;
    }
  });
}

/*
if (!('firstElementChild' in document.documentElement)) {
    Object.defineProperty(Element.prototype, 'firstElementChild', {
        get: function() {
            var element = this.firstChild;
            while (element && element.nodeType != 1) {
                element = element.nextSibling;
            }
            return element;
        }
    });
}

*/

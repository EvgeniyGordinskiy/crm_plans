
class Helper {
    ucfirst(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    validate(form, rules) {
        const formObject = $(form);
        const formInputs = formObject.serializeArray();
        const errors = {};
        console.log(rules);
        Object.keys(rules).map(function(inputName) {
            formInputs.map(function(formInput) {
                console.log(formInput);
                console.log(inputName);
                if (formInput.name === inputName) {
                    if (rules[inputName].type = 'string') {
                        switch (rules[inputName].rule) {
                            case '>': {
                                if (formInput.value.trim().length < rules[inputName].target) {
                                    errors[formInput.name] = $.app.get('helper').ucfirst(formInput.name.replace('_', ' ')) + ' should be greater than ' + (+rules[inputName].target - 1);
                                    $.app.get('helper').appendError(formObject, formInput, errors[formInput.name]);
                                }
                                break;
                            }
                            case '<': {
                                if (formInput.value.trim().length > rules[inputName].target) {

                                    errors[formInput.name] = $.app.get('helper').ucfirst(formInput.name.replace('_', ' ')) + ' should be less than ' + +rules[inputName].target - 1;
                                    $.app.get('helper').appendError(formObject, formInput, errors[formInput.name]);
                                }
                                break;
                            }
                        }
                    }
                }
            });
        });
        console.log(errors);
        return Object.keys(errors).length === 0;
    }
    appendError(formObject, formInput, error) {
        $.app.get('helper').clearError(formObject, formInput.name);
        formObject.find('[name="'+formInput.name+'"]').closest('.form-group').append('<span class="error">'+error+'</span>');
    }

    clearError(formObject, inputName) {
        const formGroup = formObject.find('[name="'+inputName+'"]').closest('.form-group');
        formGroup.find('.error').remove();
    }

    startEditing(event, formSelector, inputName) {
        event.stopPropagation();
        event.preventDefault();
        const form = $(formSelector);
        form.find('[name="'+inputName+'"]').removeAttr('disabled');
        const formGroup =  $(event.target).closest('.form-group');
        if (formGroup) {
            formGroup.find('.buttons-block-edit-pencil').css({display: 'none'});
            formGroup.find('.buttons-block-edit-active').css({display: 'block'});
        }
    }
    // finishEditing(event, formSelector, inputName, originValue) {
    //     event.stopPropagation();
    //
    // }
    cancelEditing(event, formSelector, inputName, originValue) {
        if (event) {
            event.stopPropagation();
            event.preventDefault();
        }
        const form = $(formSelector);
        const input = form.find('[name="'+inputName+'"]');
        input.attr('disabled', 'disabled');
        switch (inputName) {
            case 'plan_name': {
                if (Helper.last_plan_name) {
                    originValue = Helper.last_plan_name;
                }
                break;
            }
            case 'plan_description': {
                if (Helper.last_plan_description) {
                    originValue = Helper.last_plan_description;
                }
                break;
            }
        }
        input.val(originValue);
        const formGroup =  input.closest('.form-group');
        if (formGroup) {
            formGroup.find('.buttons-block-edit-pencil').css({display: 'block'});
            formGroup.find('.buttons-block-edit-active').css({display: 'none'});
        }
    }
}
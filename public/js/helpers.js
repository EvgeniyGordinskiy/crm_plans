
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
                    rules[inputName].map(function (rule) {
                        switch (rule.type) {
                            case 'string': {
                                switch (rule.rule) {
                                    case '>': {
                                        if (formInput.value.trim().length < rule.target) {
                                            errors[formInput.name] = $.app.get('helper').ucfirst(formInput.name.replace('_', ' ')) + ' should be greater than ' + (+rule.target - 1);
                                            $.app.get('helper').appendError(formObject, formInput, errors[formInput.name]);
                                        }
                                        break;
                                    }
                                    case '<': {
                                        if (formInput.value.trim().length > rule.target) {
                                            errors[formInput.name] = $.app.get('helper').ucfirst(formInput.name.replace('_', ' ')) + ' should be less than ' + rule.target;
                                            $.app.get('helper').appendError(formObject, formInput, errors[formInput.name]);
                                        }
                                        break;
                                    }
                                }
                                break;
                            }
                            case 'email': {
                                if (!$.app.get('helper').validateEmail(formInput.value.trim())) {
                                    errors[formInput.name] = 'Invalid Email';
                                    $.app.get('helper').appendError(formObject, formInput, errors[formInput.name]);
                                }
                            }
                        }

                    });
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
            formGroup.find('.glyphicon-fullscreen').css({display: 'none'});
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
            formGroup.find('.glyphicon-fullscreen').css({display: 'block'});
            formGroup.find('.buttons-block-edit-pencil').css({display: 'block'});
            formGroup.find('.buttons-block-edit-active').css({display: 'none'});
        }
    }

    openConfirmModal(source, id) {
        $.app.get('loader').request('part/confirm/'+source+'/'+id, null, 'body', 'get', function () {
            $('.confirm-modal').modal('toggle');
        });
    }
    validateEmail(email) {
        const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return re.test(String(email).toLowerCase());
    }

    refreshOnCliickListener(formSelector, inputName, value) {
        const input = $(formSelector).find('[name="'+inputName+'"]');
        const cancelButton = input.closest('.form-group').find('.cancelEditing');
        $.app.get('helper').cancelEditing(null, formSelector, inputName, value);
        console.log(cancelButton);
        cancelButton.removeAttr('onclick');
        cancelButton.unbind('click');
        cancelButton.click(function(formSelector, inputName, value) {
            $.app.get('helper').cancelEditing(null, formSelector, inputName, value);
        }.bind(this, formSelector, inputName, value));
    }

    refreshDragAbleAndSortable(plan_id) {
        if (plan_id) {
            this.plan_id = plan_id;
        } else {
            plan_id = this.plan_id;
        }
        console.log(plan_id);
        const days = $('.edit-plan-modal-days');
        days.sortable({
            items: '.day',
            axis: 'y',
            update: function (e, ui) {
                const data = {};
                console.log('!!!!!!!!', e, ui);
                const idData = ui.item[0].getAttribute('id');
                data['item_id'] = +idData.substring(idData.indexOf("_")+1);
                data['parent_id'] = +plan_id;
                data['resource'] = 'day';
                const sortedItems = $('.edit-plan-modal-days').sortable("toArray");
                sortedItems.map(function (item,i) {
                    item = item.substring(item.indexOf("_")+1);
                    console.log(item);
                    if (+item === data.item_id) {
                        if (sortedItems[i+1]) {
                            data['before_item_id'] = sortedItems[i+1].substring(sortedItems[i+1].indexOf("_")+1);
                        }
                        if (sortedItems[i-1]) {
                            data['after_item_id'] = sortedItems[i-1].substring(sortedItems[i-1].indexOf("_")+1);
                        }
                    }
                });
                $.app.get('loader').request('part/order', data, '.edit-plan-modal-days', 'post', function() {
                    const buttonDay = $('.add-day-exercise-button-day');
                    const buttonExercise = $('.add-day-exercise-button-exercise');
                    const buttonHtmlDay = buttonDay.wrap('<p/>').parent().html();
                    const buttonHtmlexercise = buttonExercise.wrap('<p/>').parent().html();
                    buttonDay.remove();
                    buttonExercise.remove();
                    $('.edit-plan-modal-exercises').append(buttonHtmlexercise).find('p').remove();
                    $('.edit-plan-modal-days').append(buttonHtmlDay).find('p').remove();
                    $.app.get('helper').refreshDragAbleAndSortable();
                }, null, true, '.day');
            }.bind(plan_id)
        });
        $('.edit-plan-modal-exercises').find('.exercise').each(function(key, exr) {
            $(exr).draggable({
                connectToSortable: '.day',
                start: function(e, ui) {
                    $.app.get('helper').addExercise(null, null, true);
                },
                stop: function(e, ui) {
                    console.log('exercise', e, ui);
                    $.app.get('helper').addExercise($(e.target).attr('id'), null);
                },
                helper: 'clone'
            })
        });
        days.find('.day').each(function(key, dayItem) {
            const day = $(dayItem);
            day.sortable({
                items: '.exercise',
                update: function (e, ui) {
                    console.log('day sort exercise',e,ui);
                    const data = {};
                    const idData = ui.item[0].getAttribute('id');
                    if (idData) {
                        data['item_id'] = +idData.substring(idData.lastIndexOf("_") + 1);
                        data['parent_id'] = +day.attr('id').substring(day.attr('id').indexOf("_")+1);
                        data['resource'] = 'exercise';
                        const sortedItems = day.sortable("toArray");
                        sortedItems.map(function (item, i) {
                            item = item.substring(item.lastIndexOf("_") + 1);
                            console.log(item);
                            if (+item === data.item_id) {
                                if (sortedItems[i + 1]) {
                                    data['before_item_id'] = sortedItems[i + 1].substring(sortedItems[i + 1].lastIndexOf("_") + 1);
                                }
                                if (sortedItems[i - 1]) {
                                    data['after_item_id'] = sortedItems[i - 1].substring(sortedItems[i - 1].lastIndexOf("_") + 1);
                                }
                            }
                        });
                        console.log(day.attr('id'));
                        $.app.get('loader').request('part/order', data, '.day-exercises-'+data['parent_id'], 'post', function () {
                            const buttonDay = $('.add-day-exercise-button-day');
                            const buttonExercise = $('.add-day-exercise-button-exercise');
                            const buttonHtmlDay = buttonDay.wrap('<p/>').parent().html();
                            const buttonHtmlexercise = buttonExercise.wrap('<p/>').parent().html();
                            buttonDay.remove();
                            buttonExercise.remove();
                            $('.edit-plan-modal-exercises').append(buttonHtmlexercise).find('p').remove();
                            $('.edit-plan-modal-days').append(buttonHtmlDay).find('p').remove();
                        }, null, true, '.exercise');
                    }
                }
            });
            day.droppable({
                drop: function(e, ui) {
                    console.log('day new exercise', e, ui);
                    $.app.get('helper').addExercise(null, $(e.target).attr('id'));
                    // console.log($(e.target).attr('id'));
                },
            });
        });
    }

    addExercise(exercise_id, day_id, refresh) {
        if (refresh) {
            this.day_id = null;
            this.exercise_id = null;
        }
        if (day_id) {
            this.day_id = day_id;
        }
        if (exercise_id) {
            this.exercise_id = exercise_id;
        }
        if ( this.day_id && this.exercise_id) {
            const data = {};
            data['day_id'] = +this.day_id.substring(this.day_id.indexOf("_")+1);
            data['exercise_id'] = +this.exercise_id.substring(this.exercise_id.indexOf("_")+1);
            if (!isNaN(data['day_id']) && !isNaN(data['exercise_id'])) {
                $('#day_'+data['day_id']).find('.exercise').remove();
                $.app.get('loader').request('days/exercise', data, '.day-exercises-' + data['day_id'], 'post', null, null, true, '.exercise');
                this.exercise_id = null;
                this.day_id = null;
            }
        }
    }
}
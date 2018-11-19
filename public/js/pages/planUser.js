$(document).ready(function () {
    console.log('plan connected');
    $('.edit-plan-modal-days').sortable();
});

var modalCreateSelector;

function openCreateModal(selector, source = 'plan', planId) {
    modalCreateSelector =  selector;
    console.log(selector);
    const data = {
        source: source
    };
    if (source === 'day' || source === 'exercise') {
        data['plan_id'] = planId;
    }
    $.app.get('loader').request('part/createPlanExercise', data, 'body', 'get',  function (modalCreateSelector){
        $(modalCreateSelector).modal('toggle');
    }.bind(this, [modalCreateSelector]));
}
function openEditModal (selector, planId, source = 'plan') {
    Helper.last_plan_description = null;
    Helper.last_plan_name = null;
    let route = 'part/editPlan/'+planId;
    if (source === 'user') {
        route = 'part/editUser/'+planId
    }
    $.app.get('loader').request(route, {}, 'body', 'get', function (selector){
        $(selector).modal('toggle');
    }.bind(this, [selector]));
}
function saveForm(source, formSelector, type, id, planId) {
    const data = {};
    const rules = {};
    console.log(source);
    const inputWithValidating = ['name', 'description'];
    $(formSelector).serializeArray().map(function (input) {
        data[source+ '_' +input.name] = input.value;
        if (inputWithValidating.includes(input.name)) {
            rules[input.name] = [{
                type: 'string',
                rule: '>',
                target: 3
            },
            {
                    type: 'string',
                    rule: '<',
                    target: 190
            }];
        }
    });
    if (source === 'user') {
        rules['user_email'] = [
            {
                type: 'email'
            }
        ]
    }
    if ($.app.get('helper').validate(formSelector, rules)) {
        const callBackError = function(e) {
            if (e.responseJSON.errors) {
                Object.keys(e.responseJSON.errors).map(function(input) {
                    if ($('[name='+input+']')) {
                        $.app.get('helper').appendError($('.plan-details-modal-form'), input.substr(input.lastIndexOf(source) + source.length + 1), e.responseJSON.errors[input][0]);
                    }
                });
            }
        }.bind(source);
        switch (type) {
            case 'create': {
                let route =  'plans';
                let wrapper = '.plans-wrapper';
                switch (source) {
                    case 'day': {
                         route = 'days';
                         wrapper = '.edit-plan-modal-days';
                         data['plan_id'] = planId;
                         break;
                    }
                    case 'exercise': {
                         route = 'exercises';
                         wrapper = '.edit-plan-modal-exercises';
                         data['plan_id'] = planId;
                         break;
                    }
                    case 'user': {
                         route = 'users';
                         wrapper = '.users-wrapper';
                         break;
                    }
                }
                $.app.get('loader').request(route,data, wrapper, 'post', function() {
                    $(modalCreateSelector).modal('hide');
                    $('.plan-details-modal').remove();
                    if (source === 'day' || source === 'exercise') {
                        const buttonDay = $('.add-day-exercise-button-day');
                        const buttonExercise = $('.add-day-exercise-button-exercise');
                        const buttonHtmlDay = buttonDay.wrap('<p/>').parent().html();
                        const buttonHtmlexercise = buttonExercise.wrap('<p/>').parent().html();
                        buttonDay.remove();
                        buttonExercise.remove();
                        const editPlanExc = $('.edit-plan-modal-exercises');
                        const editPlanDays = $('.edit-plan-modal-days');
                        editPlanExc.append(buttonHtmlexercise).find('p').remove();
                        editPlanDays.append(buttonHtmlDay).find('p').remove();
                        $.app.get('helper').refreshDragAbleAndSortable();
                    }
                }.bind(this, [modalCreateSelector, source, wrapper]), callBackError);
                break;
            }
        }
    }
}
function finishEditing(event, formSelector, inputName, source, originData, id) {
    event.stopPropagation();
    event.preventDefault();
    const data = {};
    const input = $(formSelector).find('[name="'+inputName+'"]');
    const validatesInputs = ['plan_name', 'plan_description', 'day_name', 'exercise_name', 'user_name'];
    data[inputName] = input.val();
    data['id'] = id;
    const rules = {};
    if (validatesInputs.includes(inputName)) {
        rules[inputName] = [{
            type: 'string',
            rule: '>',
            target: 3
        },
        {
            type: 'string',
            rule: '<',
            target: 190
        }];
    }
    if (inputName === 'user_email' || inputName === 'email') {
        rules['user_email'] = [
            {
                type: 'email'
            }
        ]
    }
    let callBack = null;
    switch (source) {
        case 'plans': {
            callBack = planEditCallBack.bind(this, inputName, data[inputName], id);
            break;
        }
        case 'days': {
            callBack =  dayExerciseEditCallBack.bind(this, formSelector, inputName, data[inputName]);
            break;
        }
        case 'exercises': {
            callBack =  dayExerciseEditCallBack.bind(this, formSelector, inputName, data[inputName], data['id']);
            break;
        }
        case 'users': {
            callBack =  userEditCallBack.bind(this, formSelector, inputName, data[inputName], id);
            break;
        }
    }
    if ($.app.get('helper').validate(formSelector, rules)) {
        const callBackError = function(e) {
            if (e.responseJSON.errors) {
                Object.keys(e.responseJSON.errors).map(function(input) {
                    if ($('[name='+input+']')) {
                        $.app.get('helper').appendError($('.edit-plan-name-description'), input, e.responseJSON.errors[input][0]);
                    }
                });
            }
        }.bind(source);
        $.app.get('loader').request(source, data, null, 'put', callBack, callBackError, false);
    }
}

function planEditCallBack(inputName, value, id) {
    const plans = $('.plan');
    Object.keys(plans).map(function (key) {
        const input = $(plans[key]).find('[name="plan_id"]');
        if (input && +input.val() === +id) {
            switch (inputName) {
                case 'plan_description': {
                    $(plans[key]).find('.plan-description').text(value);
                    Helper.last_plan_description = value;
                    break;
                }
                case 'plan_name': {
                    $(plans[key]).find('.plan-title').text(value);
                    Helper.last_plan_name = value;
                    break;
                }
            }
        }
    });
    $.app.get('helper').cancelEditing(null, '.edit-plan-name-description', inputName, value);
}

function userEditCallBack(formSelector, inputName, value, id) {
    const users = $('.user');
    console.log(formSelector, inputName, value)
    $.app.get('helper').refreshOnCliickListener(formSelector, inputName, value);
    Object.keys(users).map(function (key) {
        const input = $(users[key]).find('[name="user_id"]');
        if (input && +input.val() === +id) {
            switch (inputName) {
                case 'user_name': {
                    $(users[key]).find('.user-title').text(value);
                    Helper.last_plan_name = value;
                    break;
                }
                case 'user_email': {
                    $(users[key]).find('.user-email').text(value);
                    Helper.last_plan_description = value;
                    break;
                }
            }
        }
    });
    console.log(inputName, value);
    $.app.get('helper').cancelEditing(null, '.edit-plan-name-description', inputName, value);
}

function dayExerciseEditCallBack(formSelector, inputName, value, id) {
    $.app.get('helper').refreshOnCliickListener(formSelector, inputName, value);
    if (id) {
        $('[name=exercise_' + id + ']').closest('.exercise').find('[name=' + inputName + ']').val(value);
    }
}

function deleteAction(source, id) {
    let callBack;
    let route;
    switch (source) {
        case 'plan': {
            route = 'plans';
            callBack = function() {
                $('#plan_'+id).remove();
                $('.confirm-modal').modal('toggle').remove();
            }.bind(id);
            break;
        }
        case 'day': {
            route = 'days';
            callBack = function() {
                $('#day_'+id).remove();
                $('.confirm-modal').modal('toggle').remove();
            }.bind(id);
            break;
        }
        case 'exercise': {
            route = 'exercises';
            callBack = function() {
                $('#exercise_'+id).remove();
                $('[name=exercise_' + id + ']').closest('.exercise').remove();
                $('.confirm-modal').modal('toggle').remove();
            }.bind(id);
            break;
        }
        case 'exercise_inst': {
            route = 'days/exercise';
            callBack = function() {
                $('#exercise_inst_'+id).remove();
                $('.confirm-modal').modal('toggle').remove();
            }.bind(id);
            break;
        }
        case 'user': {
            route = 'users';
            callBack = function() {
                $('#user_'+id).remove();
                $('.confirm-modal').modal('toggle').remove();
            }.bind(id);
            break;
        }
        case 'user_plan': {
            route = 'plans/user';
            const oldId = id;
            id = id.replace('_','/');
            callBack = function() {
                console.log(oldId);
                $('#user_plan_'+oldId).remove();
                $('.confirm-modal').modal('toggle').remove();
            }.bind(oldId);
            break;
        }
    }
    $.app.get('loader').request(route+'/'+id, null, null, 'delete', callBack, null, false);
}

function loadAllPlans(user_id) {
    $('.load-all-classes').css({display: 'none'});
    $('.load-users-classes').css({display: 'block'});
    $.app.get('loader').request('plans/preview/'+user_id, null, '.plan-view-wrapper', 'get', null, null, true, '.preview-plan');
}


function backToUsersPlans(user_id) {
    $('.load-all-classes').css({display: 'block'});
    $('.load-users-classes').css({display: 'none'});
    $.app.get('loader').request('plans/user/'+user_id, null, '.plan-view-wrapper', 'get', null, null, true, '.preview-plan');
}

function inviteToProgram (user_id, plan_id) {
    console.log(user_id, plan_id);
    const data = {
        user_id: user_id,
        plan_id: plan_id,
    };
    $.app.get('loader').request('invite', data, null, 'post', function () {
        console.log($.app.get('loader').lastResponse.data);
    }, null, false);
}

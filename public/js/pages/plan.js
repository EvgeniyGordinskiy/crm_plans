$(document).ready(function () {
    console.log('plan connected');
    $('.plan').click(function (e) {
        console.log(e);
    });
});

var modalCreateSelector;

function openCreateModal(selector, source = 'plan', planId) {
    modalCreateSelector =  selector;
    console.log(selector);
    const data = {
        source: source
    };
    if (source === 'day') {
        data['plan_id'] = planId;
    }
    $.app.get('loader').request('part/createPlanExercise', data, 'body', 'get',  function (modalCreateSelector){
        $(modalCreateSelector).modal('toggle');
    }.bind(this, [modalCreateSelector]));
}
function openEditModal (selector, planId) {
    Helper.last_plan_description = null;
    Helper.last_plan_name = null;
    $.app.get('loader').request('part/editPlan/'+planId, {}, 'body', 'get', function (selector){
        $(selector).modal('toggle');
    }.bind(this, [selector]));
}
function saveForm(source, formSelector, type, id, planId) {
    const data = {};
    const rules = {};
    const inputWithValidating = ['name', 'description'];
    $(formSelector).serializeArray().map(function (input) {
        data[source+ '_' +input.name] = input.value;
        if (inputWithValidating.includes(input.name)) {
            rules[input.name] = {
                type: 'string',
                rule: '>',
                target: 3
            }
        }
    });
    if ($.app.get('helper').validate(formSelector, rules)) {
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
                        $('.edit-plan-modal-exercises').append(buttonHtmlexercise);
                        $('.edit-plan-modal-days').append(buttonHtmlDay);
                    }
                }.bind(this, [modalCreateSelector, source, wrapper]));
                break;
            }
        }
    }
}
function finishEditing(event, formSelector, inputName, originData, id) {
    event.stopPropagation();
    event.preventDefault();
    const data = {};
    const input = $(formSelector).find('[name="'+inputName+'"]');
    let q = '';
    data[inputName] = input.val();
    data['id'] = id;
    const rules = {
      plan_name: {
          type: 'string',
          rule: '>',
          target: 3
      },
      plan_description: {
          type: 'string',
          rule: '>',
          target: 3
      },
    };
    if ($.app.get('helper').validate(formSelector, rules)) {
        $.app.get('loader').request('plans', data, null, 'put',
            function () {
                const plans = $('.plan');
                Object.keys(plans).map(function (key) {
                    const input = $(plans[key]).find('[name="plan_id"]');
                    if (input && +input.val() === +id) {
                        switch (inputName) {
                            case 'plan_description': {
                                $(plans[key]).find('.plan-description').text(data[inputName]);
                                Helper.last_plan_description = data[inputName];
                                break;
                            }
                            case 'plan_name': {
                                $(plans[key]).find('.plan-title').text(data[inputName]);
                                Helper.last_plan_name = data[inputName];
                                break;
                            }
                        }
                    }
                });
                $.app.get('helper').cancelEditing(null, '.edit-plan-name-description', inputName, data[inputName])
            }.bind(this, [inputName, data[inputName]]),
            function (input, originData, formSelector) {
                $(formSelector).find('[name="' + inputName + '"]').val(originData);
            }.bind(this, [formSelector, inputName, originData]), false);
    }
}
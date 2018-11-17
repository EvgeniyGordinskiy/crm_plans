<div class="modal fade edit-plan-modal" tabindex="-1" data-backdrop="false" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <form class="edit-plan-name-description">
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <div class="buttons-block-edit">
                                <h5 class="modal-title">Name</h5>
                                <button class="btn btn-primary buttons-block-edit-pencil"
                                        onclick="$.app.get('helper').startEditing(event, '.edit-plan-name-description', 'plan_name')">
                                    <span class="glyphicon glyphicon-pencil" aria-hidden="true">
                                    </span>
                                </button>
                                <div class="buttons-block-edit-active">
                                    <button class="btn btn-primary"
                                            onclick="finishEditing(event, '.edit-plan-name-description', 'plan_name', '{{$plan->plan_name ?? ''}}', {{$plan->id}})">
                                        <span class="glyphicon glyphicon-ok" aria-hidden="true">
                                        </span>
                                    </button>
                                    <button class="btn btn-primary"
                                            onclick="$.app.get('helper').cancelEditing(event, '.edit-plan-name-description', 'plan_name', '{{$plan->plan_name ?? ''}}')">
                                        <span class="glyphicon glyphicon-remove" aria-hidden="true">
                                        </span>
                                    </button>
                                </div>
                            </div>
                            <input
                                oninput="$.app.get('helper').clearError($('.edit-plan-name-description'), 'plan_name')"
                                type="text" class="form-control"
                                disabled
                                name="plan_name"
                                value="{{$plan->plan_name ?? ''}}">
                        </div>
                        <div class="form-group  col-sm-6">
                            <div class="buttons-block-edit">
                                <h5 class="modal-title">Difficulty</h5>
                                <button class="btn btn-primary buttons-block-edit-pencil"
                                        onclick="$.app.get('helper').startEditing(event, '.edit-plan-name-description', 'plan_difficulty')">
                                    <span class="glyphicon glyphicon-pencil" aria-hidden="true">
                                    </span>
                                </button>
                                <div class="buttons-block-edit-active">
                                    <button class="btn btn-primary"
                                            onclick="finishEditing(event, '.edit-plan-name-description', 'plan_difficulty', '{{$plan->plan_difficulty ?? ''}}', {{$plan->id}})">
                                        <span class="glyphicon glyphicon-ok" aria-hidden="true">
                                        </span>
                                    </button>
                                    <button class="btn btn-primary"
                                            onclick="$.app.get('helper').cancelEditing(event, '.edit-plan-name-description', 'plan_difficulty', '{{$plan->plan_difficulty ?? ''}}')">
                                        <span class="glyphicon glyphicon-remove" aria-hidden="true">
                                        </span>
                                    </button>
                                </div>
                            </div>
                            <select class="form-control" name="plan_difficulty" disabled>
                                <option @If ($plan->plan_difficulty === 1) selected @endif value="1">Beginner</option>
                                <option  @If ($plan->plan_difficulty === 2) selected @endif value="2">Intermediate</option>
                                <option  @If ($plan->plan_difficulty === 3) selected @endif value="3">Expert</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="buttons-block-edit">
                            <h5 class="modal-title">Description</h5>
                            <button class="btn btn-primary buttons-block-edit-pencil"
                                    onclick="$.app.get('helper').startEditing(event, '.edit-plan-name-description', 'plan_description')">
                                <span class="glyphicon glyphicon-pencil" aria-hidden="true">
                                </span>
                            </button>
                            <div class="buttons-block-edit-active">
                                <button class="btn btn-primary"
                                        onclick="finishEditing(event, '.edit-plan-name-description', 'plan_description', '{{$plan->plan_description ?? ''}}', {{$plan->id}})">
                                    <span class="glyphicon glyphicon-ok" aria-hidden="true">
                                    </span>
                                </button>
                                <button class="btn btn-primary"
                                        onclick="$.app.get('helper').cancelEditing(event, '.edit-plan-name-description', 'plan_description', '{{$plan->plan_description ?? ''}}')">
                                    <span class="glyphicon glyphicon-remove" aria-hidden="true">
                                    </span>
                                </button>
                            </div>
                        </div>
                           <textarea oninput="$.app.get('helper').clearError($('.edit-plan-name-description'), 'plan_description')"
                                     class="form-control"
                                     disabled
                                     name="plan_description">{{$plan->plan_description ?? ''}}</textarea>
                    </div>
                </form>
                {{--<button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
                {{--<span aria-hidden="true">&times;</span>--}}
                {{--</button>--}}
            </div>
            <div class="modal-body edit-plan-modal-modal-body raw">
                <div class="col-sm-7 edit-plan-modal-days">
                    @include('parts.day', compact('days'))
                    <button type="button"
                            class="btn btn-link add-day-exercise-button-day"
                            onclick="openCreateModal('.plan-details-modal', 'day', {{$plan->id}})">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                        Add Day
                    </button>
                </div>
                <div class="col-sm-5 edit-plan-modal-exercises">
                    <button type="button"
                            class="btn btn-link add-day-exercise-button add-day-exercise-button-exercise"
                            onclick="openCreateModal('.plan-details-modal', 'exercise')">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                        Add Exercise
                    </button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"
                    onclick="$('.edit-plan-modal').remove()">Close</button>
                {{--<button type="button" class="btn btn-primary" onclick="saveForm('{{$type}}', '.plan-details-modal-form')">Save</button>--}}
            </div>
        </div>
    </div>
</div>
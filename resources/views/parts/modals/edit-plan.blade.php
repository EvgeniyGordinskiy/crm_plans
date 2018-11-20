<div class="modal fade edit-plan-modal" tabindex="-1" data-backdrop="false" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <form class="edit-plan-name-description" onsubmit="return false">
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <div class="buttons-block-edit">
                                <h5 class="modal-title">Name</h5>
                                <button class="btn btn-primary buttons-block-edit-pencil"
                                        type="button"
                                        onclick="$.app.get('helper').startEditing(event, '.edit-plan-name-description', '{{$source === 'plan' ? 'plan_name' : 'user_name'}}')">
                                    <span class="glyphicon glyphicon-pencil" aria-hidden="true">
                                    </span>
                                </button>
                                <div class="buttons-block-edit-active">
                                    <button class="btn btn-primary"
                                            type="button"
                                            onclick="finishEditing(event, '.edit-plan-name-description', '{{$source === 'plan' ? 'plan_name' : 'user_name'}}', '{{$source === 'plan' ? 'plans' : 'users'}}', '{{$item->plan_name ?? $item->name ?? ''}}', {{$item->id}})">
                                        <span class="glyphicon glyphicon-ok" aria-hidden="true">
                                        </span>
                                    </button>
                                    <button class="btn btn-primary cancelEditing"
                                            type="button"
                                            onclick="$.app.get('helper').cancelEditing(event, '.edit-plan-name-description', '{{$source === 'plan' ? 'plan_name' : 'user_name'}}', '{{$item->plan_name ?? $item->name ?? ''}}')">
                                        <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                                    </button>
                                </div>
                            </div>
                            <input
                                oninput="$.app.get('helper').clearError($('.edit-plan-name-description'), '{{$source === 'plan' ? 'plan_name' : 'user_name'}}')"
                                type="text" class="form-control"
                                disabled
                                name="{{$source === 'plan' ? 'plan_name' : 'user_name'}}"
                                value="{{$item->plan_name ?? $item->name ?? ''}}">
                        </div>
                        <div class="form-group  col-sm-6">
                            <div class="buttons-block-edit">
                                <h5 class="modal-title">@if($source === 'plan') Difficulty @endif @if ($source === 'user') Email @endif</h5>
                                <button class="btn btn-primary buttons-block-edit-pencil"
                                        type="button"
                                        onclick="$.app.get('helper').startEditing(event, '.edit-plan-name-description', '{{$source === 'plan' ? 'plan_difficulty' : 'user_email'}}')">
                                    <span class="glyphicon glyphicon-pencil" aria-hidden="true">
                                    </span>
                                </button>
                                <div class="buttons-block-edit-active">
                                    <button class="btn btn-primary"
                                            type="button"
                                            onclick="finishEditing(event, '.edit-plan-name-description', '{{$source === 'plan' ? 'plan_difficulty' : 'user_email'}}', '{{$source === 'plan' ? 'plans' : 'users'}}', '{{$item->plan_difficulty ?? $item->email ?? ''}}', {{$item->id}})">
                                        <span class="glyphicon glyphicon-ok" aria-hidden="true">
                                        </span>
                                    </button>
                                    <button class="btn btn-primary cancelEditing"
                                            type="button"
                                            onclick="$.app.get('helper').cancelEditing(event, '.edit-plan-name-description', '{{$source === 'plan' ? 'plan_difficulty' : 'user_email'}}', '{{$item->plan_difficulty ?? $item->email ?? ''}}')">
                                        <span class="glyphicon glyphicon-remove" aria-hidden="true">
                                        </span>
                                    </button>
                                </div>
                            </div>
                            @if ($source === 'plan')
                                <select class="form-control" name="plan_difficulty" disabled>
                                    <option @If ($item->plan_difficulty === 1) selected @endif value="1">Beginner</option>
                                    <option  @If ($item->plan_difficulty === 2) selected @endif value="2">Intermediate</option>
                                    <option  @If ($item->plan_difficulty === 3) selected @endif value="3">Expert</option>
                                </select>
                            @endif
                            @if ($source === 'user')
                                <input
                                        oninput="$.app.get('helper').clearError($('.edit-plan-name-description'), 'user_email')"
                                        type="text" class="form-control"
                                        disabled
                                        name="user_email"
                                        value="{{$item->email ?? ''}}">
                            @endif
                        </div>
                    </div>
                    @if ($source === 'plan')
                        <div class="form-group">
                            <div class="buttons-block-edit">
                                <h5 class="modal-title">Description</h5>
                                <button class="btn btn-primary buttons-block-edit-pencil"
                                        type="button"
                                        onclick="$.app.get('helper').startEditing(event, '.edit-plan-name-description', 'plan_description')">
                                    <span class="glyphicon glyphicon-pencil" aria-hidden="true">
                                    </span>
                                </button>
                                <div class="buttons-block-edit-active">
                                    <button class="btn btn-primary"
                                            type="button"
                                            onclick="finishEditing(event, '.edit-plan-name-description', 'plan_description', 'plans', '{{$item->plan_description ?? ''}}', {{$item->id}})">
                                        <span class="glyphicon glyphicon-ok" aria-hidden="true">
                                        </span>
                                    </button>
                                    <button class="btn btn-primary"
                                            type="button"
                                            onclick="$.app.get('helper').cancelEditing(event, '.edit-plan-name-description', 'plan_description', '{{$item->plan_description ?? ''}}')">
                                        <span class="glyphicon glyphicon-remove" aria-hidden="true">
                                        </span>
                                    </button>
                                </div>
                            </div>
                               <textarea oninput="$.app.get('helper').clearError($('.edit-plan-name-description'), 'plan_description')"
                                         class="form-control"
                                         disabled
                                         name="plan_description">{{$item->plan_description ?? ''}}</textarea>
                        </div>
                    @endif
                </form>
                {{--<button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
                {{--<span aria-hidden="true">&times;</span>--}}
                {{--</button>--}}
            </div>
            <div class="modal-body edit-plan-modal-modal-body">
                @if ($source === 'plan')
                    <div class="raw">
                    @include('parts.plan-edit-body', compact('exercises', 'item', 'exercises', 'days'))
                        </div>
                @endif
                @if ($source === 'user')
                        <div class="raw plan-view-wrapper">
                            <button type="button"
                                    class="btn btn-success load-all-classes"
                                    onclick="loadAllPlans({{$item->id}})">
                                <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                            </button>
                            <button type="button"
                                    class="btn btn-success load-users-classes"
                                    onclick="backToUsersPlans({{$item->id}})">
                                <span class="glyphicon glyphicon-arrow-left" aria-hidden="true"></span>
                            </button>
                            @include('parts.plan-view-body', ['plans' => $plans, 'user_id' => $item->id])
                        </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"
                    onclick="$('.edit-plan-modal').remove()">Close</button>
                {{--<button type="button" class="btn btn-primary" onclick="saveForm('{{$type}}', '.plan-details-modal-form')">Save</button>--}}
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function () {
        console.log('plan connected');
        @if ($source === 'plan')
            $.app.get('helper').refreshDragAbleAndSortable({{$item->id}});
        @endif
    });
</script>
@foreach ($exercises as $exercise)
    <div class="exercise"  id="{{$exercise->inst_id ? 'exercise_inst_' . $exercise->inst_id : 'exercise_'. $exercise->id}}">
        <form   @if(!isset($exercise->inst_id))  class="change-exercise-name-{{$exercise->id}}" @endif onsubmit="return false">
            <div class="form-group">
                @if (!isset($view) || !$view)
                    <div class="buttons-block-edit">
                        @if(!isset($exercise->inst_id))
                            <button class="btn btn-primary buttons-block-edit-pencil"
                                    type="button"
                                    onclick="$.app.get('helper').startEditing(event, '.change-exercise-name-{{$exercise->id}}', 'exercise_name')">
                                <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                            </button>
                        @endif
                        <button class="btn btn-primary buttons-block-edit-pencil"
                                    type="button"
                                    onclick="$.app.get('helper').openConfirmModal('{{$exercise->inst_id ? 'exercise_inst' : 'exercise'}}', {{$exercise->inst_id ?? $exercise->id}})">
                                <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                        </button>
                        <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
                        <div class="buttons-block-edit-active">
                            <button class="btn btn-primary"
                                    type="button"
                                    onclick="finishEditing(event, '.change-exercise-name-{{$exercise->id}}', 'exercise_name', 'exercises', '{{$exercise->exercise_name ?? ''}}', {{$exercise->id}})">
                                    <span class="glyphicon glyphicon-ok" aria-hidden="true">
                                    </span>
                            </button>
                            <button class="btn btn-primary cancelEditing"
                                    type="button"
                                    onclick="$.app.get('helper').cancelEditing(event, '.change-exercise-name-{{$exercise->id}}', 'exercise_name', '{{$exercise->exercise_name ?? ''}}')">
                                    <span class="glyphicon glyphicon-remove" aria-hidden="true">
                                    </span>
                            </button>
                        </div>
                    </div>
                @endif
                <input
                        oninput="$.app.get('helper').clearError($('.change-exercise-name-{{$exercise->id}}'), 'exercise_name')"
                        type="text" class="form-control"
                        disabled
                        name="exercise_name"
                        value="{{$exercise->exercise_name ?? ''}}">
            </div>
        </form>
        @if (isset($exercise->inst_id))
         <input type="hidden" name="exercise_{{$exercise->id}}">
        @endif
    </div>
@endforeach
@foreach ($days as $day)
    <div class="day"  id="day_{{$day->id}}">
        <form class="change-day-name-{{$day->id}}" onsubmit="return false">
            <div class="form-group">
                @if(!isset($view) || !$view)
                    <div class="buttons-block-edit">
                        <button class="btn btn-primary buttons-block-edit-pencil"
                                type="button"
                                onclick="$.app.get('helper').startEditing(event, '.change-day-name-{{$day->id}}', 'day_name')">
                                <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                        </button>
                        <button class="btn btn-primary buttons-block-edit-pencil"
                                type="button"
                                onclick="$.app.get('helper').openConfirmModal('day', {{$day->id}})">
                            <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                        </button>
                        <span class="glyphicon glyphicon-fullscreen" aria-hidden="true"></span>
                        <div class="buttons-block-edit-active">
                            <button class="btn btn-primary"
                                    type="button"
                                    onclick="finishEditing(event, '.change-day-name-{{$day->id}}', 'day_name', 'days', '{{$day->day_name ?? ''}}', {{$day->id}})">
                                    <span class="glyphicon glyphicon-ok" aria-hidden="true">
                                    </span>
                            </button>
                            <button class="btn btn-primary cancelEditing"
                                    type="button"
                                    onclick="$.app.get('helper').cancelEditing(event, '.change-day-name-{{$day->id}}', 'day_name', '{{$day->day_name ?? ''}}')">
                                    <span class="glyphicon glyphicon-remove" aria-hidden="true">
                                    </span>
                            </button>
                        </div>
                    </div>
                @endif
                <input
                        oninput="$.app.get('helper').clearError($('.change-day-name-{{$day->id}}'), 'day_name')"
                        type="text" class="form-control"
                        disabled
                        name="day_name"
                        value="{{$day->day_name ?? ''}}">
            </div>
        </form>
        <div class="day-exercises day-exercises-{{$day->id}}">
            @include('parts.exercise', ['exercises' => $day->exercises ?? [], 'view' => $view ?? false])
        </div>
        <input type="hidden" name="day_id" value="{{$day->id}}">
    </div>
@endforeach
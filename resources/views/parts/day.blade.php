@foreach ($days as $day)
    <div class="day">
        <form class="change-day-name-{{$day->id}}">
            <div class="form-group">
                <div class="buttons-block-edit">
                    <button class="btn btn-primary buttons-block-edit-pencil"
                            onclick="$.app.get('helper').startEditing(event, '.change-day-name-{{$day->id}}', 'day_name')">
                            <span class="glyphicon glyphicon-pencil" aria-hidden="true">
                            </span>
                    </button>
                    <div class="buttons-block-edit-active">
                        <button class="btn btn-primary"
                                onclick="finishEditing(event, '.change-day-name-{{$day->id}}', 'plan_description', '{{$day->day_name ?? ''}}', {{$day->id}})">
                                <span class="glyphicon glyphicon-ok" aria-hidden="true">
                                </span>
                        </button>
                        <button class="btn btn-primary"
                                onclick="$.app.get('helper').cancelEditing(event, '.change-day-name-{{$day->id}}', 'plan_description', '{{$day->day_name ?? ''}}')">
                                <span class="glyphicon glyphicon-remove" aria-hidden="true">
                                </span>
                        </button>
                    </div>
                </div>
                <input
                        oninput="$.app.get('helper').clearError($('.change-day-name-{{$day->id}}'), 'day_name')"
                        type="text" class="form-control"
                        disabled
                        name="day_name"
                        value="{{$day->day_name ?? ''}}">
            </div>
        </form>
        <div class="day-exercises">
            ddd
        </div>
        <input type="hidden" name="plan_id" value="{{$day->id}}">
    </div>
@endforeach
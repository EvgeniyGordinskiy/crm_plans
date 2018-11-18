<div class="col-sm-7 edit-plan-modal-days">
    @include('parts.day', compact('days'))
    <button type="button"
            class="btn btn-link add-day-exercise-button-day"
            onclick="openCreateModal('.plan-details-modal', 'day', {{$item->id}})">
        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
        Add Day
    </button>
</div>
<div class=" edit-plan-modal-exercises col-sm-5">
    @include('parts.exercise', compact('exercises'))
    <button type="button"
            class="btn btn-link add-day-exercise-button add-day-exercise-button-exercise"
            onclick="openCreateModal('.plan-details-modal', 'exercise', {{$item->id}})">
        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
        Add Exercise
    </button>
</div>
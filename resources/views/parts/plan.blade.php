@foreach ($plans as $plan)
<div class="col-xs-12 col-sm-6 col-md-4" id="plan_{{$plan->id}}">
    <div class="plan">
        <button class="btn btn-primary edit-plan-button"
            onclick="openEditModal('.edit-plan-modal', {{$plan->id}})">
            <span class="glyphicon glyphicon-pencil" aria-hidden="true">
            </span>
        </button>
        <button class="btn btn-primary delete-plan-button"
                type="button"
                onclick="$.app.get('helper').openConfirmModal('plan', {{$plan->id}})">
            <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
        </button>
        <h5 class="plan-title">{{$plan->plan_name}}</h5>
        <p class="plan-description">
            {{$plan->plan_description}}
        </p>
        <input type="hidden" name="plan_id" value="{{$plan->id}}">
    </div>
</div>
@endforeach
@foreach ($plans as $plan)
    <div class=" col-xs-10 col-sm-5 plan preview-plan" @if (isset($plan->user_id) && $plan->user_id === $user_id) id="user_plan_{{$plan->id.'_'.$user_id}}" @endif>
        @if (isset($plan->user_id) && $plan->user_id === $user_id)
            <button class="btn btn-primary edit-user-plan"
                    type="button"
                    onclick="$.app.get('helper').openConfirmModal('user_plan', '{{$plan->id.'_'.$user_id}}')">
                <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
            </button>
        @endif
        <div class="preview-plan-header">
            <h5>{{$plan->plan_name}}</h5>
            <h5>{{$plan->plan_difficulty === 1 ? 'Beginner' : $plan->plan_difficulty === 2 ? 'Intermediate' : 'Expert'}}</h5>
        </div>
        <div class="preview-plan-body">
            @include('parts.day', ['days' => $plan->get_days_with_exercises(), 'view' => true])
        </div>
        @if (isset($user_id) && $user_id && (!isset($plan->user_id) || $plan->user_id !== $user_id))
            <button type="button"
                    class="btn btn-success invite-to-plan"
                    onclick="inviteToProgram({{$user_id}}, {{$plan->id}})">
                <span>Invite</span>
            </button>
        @endif
    </div>
@endforeach

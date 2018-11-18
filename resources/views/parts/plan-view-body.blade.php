@foreach ($plans as $plan)
    <div class=" col-xs-5 plan preview-plan">
        <div class="preview-plan-header">
            <h5>{{$plan->plan_name}}</h5>
            <h5>{{$plan->plan_difficulty === 1 ? 'Beginner' : $plan->plan_difficulty === 2 ? 'Intermediate' : 'Expert'}}</h5>
        </div>
        <div class="preview-plan-body">
            @include('parts.day', ['days' => $plan->get_days_with_exercises(), 'view' => true])
        </div>
        @if (isset($user_id) && $user_id)
            <button type="button"
                    class="btn btn-success invite-to-plan"
                    onclick="inviteToProgram({{$user_id}}, {{$plan->id}})">
                <span>Invite</span>
            </button>
        @endif
    </div>
@endforeach

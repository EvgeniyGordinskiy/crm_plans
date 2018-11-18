@foreach ($users as $user)
    <div class="col-xs-12 col-sm-6 col-md-4" id="user_{{$user->id}}">
        <div class="user">
            <button class="btn btn-primary edit-plan-button"
                    onclick="openEditModal('.edit-plan-modal', {{$user->id}}, 'user')">
            <span class="glyphicon glyphicon-pencil" aria-hidden="true">
            </span>
            </button>
            <button class="btn btn-primary delete-plan-button"
                    type="button"
                    onclick="$.app.get('helper').openConfirmModal('user', {{$user->id}})">
                <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
            </button>
            <h5 class="user-title">{{$user->name}}</h5>
            <h6 class="user-email">{{$user->email}}</h6>
            <input type="hidden" name="user_id" value="{{$user->id}}">
        </div>
    </div>
@endforeach
<div class="modal fade plan-details-modal" tabindex="-1" data-backdrop="false" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    {{ $type === 'create' ? 'Create ' . ucfirst($source) : "Edit $item->name"}}
                </h5>
                {{--<button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
                    {{--<span aria-hidden="true">&times;</span>--}}
                {{--</button>--}}
            </div>
            <div class="modal-body">
                <form class="plan-details-modal-form" onsubmit="return false">
                    <div class="form-group">
                        <label for="name" class="col-form-label">Name:</label>
                        <input oninput="$.app.get('helper').clearError($('.plan-details-modal-form'), 'name')"
                               type="text" class="form-control"
                               name="name"
                               value="{{$item->day_name ?? $item->exercise_name ?? ''}}">
                    </div>
                    @if ($source === 'user')
                        <div class="form-group">
                            <label for="name" class="col-form-label">Email:</label>
                            <input oninput="$.app.get('helper').clearError($('.plan-details-modal-form'), 'email')"
                                   type="text" class="form-control"
                                   name="email"
                                   value="{{$item->email ?? ''}}">
                        </div>
                    @endif
                    @if ($source !== 'day' && $source !== 'exercise' && $source !== 'user')
                        <div class="form-group">
                            <label for="description" class="col-form-label">Description:</label>
                            <textarea oninput="$.app.get('helper').clearError($('.plan-details-modal-form'), 'description')"
                                      class="form-control"
                                      name="description">
                                {{$item->plan_description ?? $item->exercise_description ?? ''}}
                            </textarea>
                        </div>
                    @endif
                    @if ($source === 'plan')
                        <div class="form-group">
                            <label for="sel1">Difficulty:</label>
                            <select class="form-control" name="difficulty">
                                <option value="1">Beginner</option>
                                <option value="2">Intermediate</option>
                                <option value="3">Expert</option>
                            </select>
                        </div>
                    @endif
                    <input type="hidden" name="id" value="{{$item->id ?? null}}">
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="$('.plan-details-modal').remove()">Close</button>
                <button type="button" class="btn btn-primary" onclick="saveForm('{{$source}}', '.plan-details-modal-form', '{{$type}}',
                {{$type === 'edit' ? $item->id : 'false'}}, {{$source === 'day' || $source === 'exercise'? $plan_id : null}})">Save</button>
            </div>
        </div>
    </div>
</div>
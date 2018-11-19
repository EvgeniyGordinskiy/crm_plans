<div class="modal fade confirm-modal" tabindex="-1" data-backdrop="false" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    Delete {{$source === 'exercise_inst' ? 'exercise from day' : $source === 'user_plan'? 'users plan' : $source}}?
                </h5>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="deleteAction('{{$source}}', '{{$id}}')">Delete</button>
            </div>
        </div>
    </div>
</div>
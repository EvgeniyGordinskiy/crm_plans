<div class="row users-wrapper">
    <button type="button"
            class="btn btn-success add-plan-button"
            onclick="openCreateModal('.plan-details-modal', 'user')">
        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
    </button>
    @include('parts.user', compact('users'))
</div>
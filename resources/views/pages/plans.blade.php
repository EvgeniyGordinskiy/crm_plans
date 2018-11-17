<div class="row plans-wrapper">
    <button type="button"
            class="btn btn-success add-plan-button"
            onclick="openCreateModal('.plan-details-modal')">
        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
    </button>
    @include('parts.plan', compact('plans'))
</div>
<div class="modal fade" id="modal_delete" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-custom">
            <div class="modal-header modal-header-custom">
                <h5 class="modal-title fw-semibold">Delete {{ $label }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body modal-body-custom">
                <form action="{{ $action }}" method="post">
                    @csrf
                    <p>Are you sure you want to delete this {{ strtolower($label) }}? This cannot be undone.</p>
                    <button type="submit" class="btn-admin-delete">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

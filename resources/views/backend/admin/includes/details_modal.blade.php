<div class="modal fade view_modal" id="{{ isset($modal_id) ? $modal_id : 'myModal' }}" tabindex="-1"
    aria-labelledby="{{ isset($modal_id) ? $modal_id + 'Label' : 'myModalLabel' }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="{{ isset($modal_id) ? $modal_id + 'Label' : 'myModalLabel' }}">
                    {{ $modal_title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="{{ isset($modal_wrap_id) ? $modal_wrap_id : 'modal_data' }}">
            </div>
        </div>
    </div>
</div>

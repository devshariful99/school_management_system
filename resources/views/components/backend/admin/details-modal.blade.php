<div class="modal fade view_modal" id="{{ isset($datas['modal_id']) ? $datas['modal_id'] : 'myModal' }}" tabindex="-1"
    aria-labelledby="{{ isset($datas['modal_id']) ? $datas['modal_id'] + 'Label' : 'myModalLabel' }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"
                    id="{{ isset($datas['modal_id']) ? $datas['modal_id'] + 'Label' : 'myModalLabel' }}">
                    {{ $datas['modal_title'] }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="{{ isset($datas['modal_wrap_id']) ? $datas['modal_wrap_id'] : 'modal_data' }}">
            </div>
        </div>
    </div>
</div>

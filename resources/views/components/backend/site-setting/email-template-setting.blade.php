<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="title">{{ _('Email Templates') }}</h5>
            </div>
            <div class="card-body">
                <table class="table table-striped datatable">
                    <thead>
                        <tr>
                            <th>{{ __('SL') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Subject') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($email_templates as $et)
                            <tr>
                                <td> {{ $loop->iteration }} </td>
                                <td> {{ $et->name }} </td>
                                <td> {{ $et->subject }} </td>
                                <td>
                                    <a class="btn btn-info btn-sm text-white edit_et" href="javascript:void(0)"
                                        data-id="{{ $et->id }}"><i class="fas fa-pen"></i></a>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<!-- Template Edit Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('Email Template') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-striped datatable">
                            <thead>
                                <tr>
                                    <th>{{ __('Variable') }}</th>
                                    <th>{{ __('Meaning') }}</th>
                                </tr>
                            </thead>
                            <tbody class="variables">
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <form id="emailTemplateForm">
                            @csrf
                            <div class="form-group">
                                <label>{{ __('Subject') }}</label>
                                <input type="text" name="subject" id="subject" class="form-control"
                                    placeholder="Enter subject" value="">
                                <x-feedback-alert :datas="['errors' => $errors, 'field' => 'subject']" />
                            </div>
                            <div class="form-group">
                                <label>{{ __('Template') }}</label>
                                <textarea name="template" id="template" class="form-control"></textarea>
                                <x-feedback-alert :datas="['errors' => $errors, 'field' => 'template']" />
                            </div>
                            <div class="form-group">
                                <span type="submit" id="updateEmailTemplate"
                                    class="btn btn-primary float-end">{{ __('Update') }}</span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('js')
    <script>
        let details = {
            edit_url: "{{ route('site_setting.email_template', ['id']) }}",
        }
    </script>
    <script src="{{ asset('backend/admin/js/email_template.js') }}"></script>
@endpush

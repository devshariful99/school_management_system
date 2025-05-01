<div class="row">
    <div class="col-md-12}}">
        <div class="card">
            <div class="card-header">
                <h5 class="title">{{ _('SMS Settings') }}</h5>
            </div>
            <form method="POST" action="{{ route('site_setting.update') }}" autocomplete="off"
                enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label>{{ _('API Url') }}</label>
                        <input type="text" name="sms_api_url"
                            class="form-control{{ $errors->has('sms_api_url') ? ' is-invalid' : '' }}"
                            placeholder="{{ _('Enter sms api url') }}" value="{{ $sms_settings['sms_api_url'] ?? '' }}">
                        <x-feedback-alert :datas="['errors' => $errors, 'field' => 'sms_api_url']" />
                    </div>
                    <div class="form-group">
                        <label>{{ _('API Key') }}</label>
                        <input type="text" name="sms_api_key"
                            class="form-control{{ $errors->has('sms_api_key') ? ' is-invalid' : '' }}"
                            placeholder="{{ _('Enter sms api key') }}" value="{{ $sms_settings['sms_api_key'] ?? '' }}">
                        <x-feedback-alert :datas="['errors' => $errors, 'field' => 'sms_api_key']" />
                    </div>
                    <div class="form-group">
                        <label>{{ _('API Secret') }}</label>
                        <input type="text" name="sms_api_secret"
                            class="form-control{{ $errors->has('sms_api_secret') ? ' is-invalid' : '' }}"
                            placeholder="{{ _('Enter sms api secret') }}"
                            value="{{ $sms_settings['sms_api_secret'] ?? '' }}">
                        <x-feedback-alert :datas="['errors' => $errors, 'field' => 'sms_api_secret']" />
                    </div>
                    <div class="form-group">
                        <label>{{ _('API Sender Id') }}</label>
                        <input type="text" name="sms_api_sender_id"
                            class="form-control{{ $errors->has('sms_api_sender_id') ? ' is-invalid' : '' }}"
                            placeholder="{{ _('Enter sms api sender id') }}"
                            value="{{ $sms_settings['sms_api_sender_id'] ?? '' }}">
                        <x-feedback-alert :datas="['errors' => $errors, 'field' => 'sms_api_sender_id']" />
                    </div>
                    <div class="form-group">
                        <label>{{ _('API status code') }}</label>
                        <input type="text" name="sms_api_status_code"
                            class="form-control{{ $errors->has('sms_api_status_code') ? ' is-invalid' : '' }}"
                            placeholder="{{ _('Enter sms api secret') }}"
                            value="{{ $sms_settings['sms_api_status_code'] ?? '' }}">
                        <x-feedback-alert :datas="['errors' => $errors, 'field' => 'sms_api_status_code']" />
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-fill btn-primary">{{ _('Save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

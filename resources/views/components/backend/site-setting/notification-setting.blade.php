@push('css')
    <style>

    </style>
@endpush
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="title">{{ _('Notification Settings') }}</h5>
            </div>
            <form method="POST" action="{{ route('site_setting.notification') }}" autocomplete="off"
                enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <div class="row text-center">
                        <div class="form-group col-md-4">
                            <div class="check_box">
                                <label>{{ _('Email Verification') }}</label><br>
                                <input type="checkbox" name="email_verification"
                                    {{ isset($notification_settings['email_verification']) && $notification_settings['email_verification'] == 1 ? 'checked' : '' }}
                                    value="1" />
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <div class="check_box">
                                <label>{{ _('SMS Verification') }}</label><br>
                                <input type="checkbox" name="sms_verification"
                                    {{ isset($notification_settings['sms_verification']) && $notification_settings['sms_verification'] == 1 ? 'checked' : '' }}
                                    value="1" />
                            </div>
                        </div>
                        <div class="form-group col-md-4">
                            <div class="check_box">
                                <label>{{ _('User Registration') }}</label><br>
                                <input type="checkbox" name="user_registration"
                                    {{ isset($notification_settings['user_registration']) && $notification_settings['user_registration'] == 1 ? 'checked' : '' }}
                                    value="1" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-fill btn-primary">{{ _('Update') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

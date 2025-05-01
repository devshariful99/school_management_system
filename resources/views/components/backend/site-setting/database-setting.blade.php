<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="title">{{ __('Database Settings') }}</h5>
            </div>
            <form method="POST" action="{{ route('site_setting.update') }}" autocomplete="off" enctype="multipart/form-data">
                @csrf
                <div class="card-body">

                    <div class="form-group {{ $errors->has('db_driver') ? ' has-danger' : '' }}">
                        <label>{{ __('Database Driver') }}</label>
                        <select name="db_driver"
                            class="form-control  no-select {{ $errors->has('db_driver') ? ' is-invalid' : '' }}">
                            <option value="mysql" @if (isset($db_settings['db_driver']) && $db_settings['db_driver'] == 'mysql') selected @endif>
                                {{ __('MySQL') }}</option>
                            <option value="pgsql" @if (isset($db_settings['db_driver']) && $db_settings['db_driver'] == 'pgsql') selected @endif>
                                {{ __('PostgreSQL') }}</option>
                            <option value="sqlite" @if (isset($db_settings['db_driver']) && $db_settings['db_driver'] == 'sqlite') selected @endif>
                                {{ __('SQLite') }}</option>
                            <option value="sqlsrv" @if (isset($db_settings['db_driver']) && $db_settings['db_driver'] == 'sqlsrv') selected @endif>
                                {{ __('SQL Server') }}</option>
                        </select>
                        <x-feedback-alert :datas="['errors' => $errors, 'field' => 'db_driver']" />
                    </div>

                    <div class="form-group {{ $errors->has('db_host') ? ' has-danger' : '' }}">
                        <label>{{ __('Database Host') }}</label>
                        <input type="text" name="db_host"
                            class="form-control {{ $errors->has('db_host') ? ' is-invalid' : '' }}"
                            placeholder="{{ __('Database Host') }}" value="{{ $db_settings['db_host'] ?? '' }}">
                        <x-feedback-alert :datas="['errors' => $errors, 'field' => 'db_host']" />
                    </div>

                    <div class="form-group {{ $errors->has('db_port') ? ' has-danger' : '' }}">
                        <label>{{ __('Database Port') }}</label>
                        <input type="text" name="db_port"
                            class="form-control {{ $errors->has('db_port') ? ' is-invalid' : '' }}"
                            placeholder="{{ __('Database Port') }}" value="{{ $db_settings['db_port'] ?? '' }}">
                        <x-feedback-alert :datas="['errors' => $errors, 'field' => 'db_port']" />
                    </div>

                    <div class="form-group{{ $errors->has('db_name') ? ' has-danger' : '' }}">
                        <label>{{ __('Database Name') }}</label>
                        <input type="" name="db_name"
                            class="form-control {{ $errors->has('db_name') ? ' is-invalid' : '' }}"
                            placeholder="{{ __('Database Name') }}" value="{{ $db_settings['db_name'] ?? '' }}">
                        <x-feedback-alert :datas="['errors' => $errors, 'field' => 'db_name']" />
                    </div>

                    <div class="form-group {{ $errors->has('db_username') ? ' has-danger' : '' }}">
                        <label>{{ __('Database Username') }}</label>
                        <input type="text" name="db_username"
                            class="form-control{{ $errors->has('db_username') ? ' is-invalid' : '' }}"
                            placeholder="{{ __('Database Username') }}"
                            value="{{ $db_settings['db_username'] ?? '' }}" autocomplete="off">
                        <x-feedback-alert :datas="['errors' => $errors, 'field' => 'db_username']" />
                    </div>

                    <div class="form-group {{ $errors->has('db_password') ? ' has-danger' : '' }}">
                        <label>{{ __('Database Password') }}</label>
                        <input type="password" name="db_password"
                            class="form-control{{ $errors->has('db_password') ? ' is-invalid' : '' }}"
                            placeholder="{{ __('Database Password') }}"
                            value="{{ $db_settings['db_password'] ?? '' }}" autocomplete="off">
                        <x-feedback-alert :datas="['errors' => $errors, 'field' => 'db_password']" />
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-fill btn-primary">{{ __('Save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@extends('backend.admin.layouts.master', ['page_slug' => 'documentation'])
@section('title', 'Edit Documentation')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="cart-title">{{ __('Edit Documentation') }}</h4>
                    <x-backend.admin.button :datas="[
                        'routeName' => 'documentation.index',
                        'label' => 'Back',
                        'permissions' => ['documentation-list', 'documentation-delete', 'documentation-details'],
                    ]" />
                </div>
                <div class="card-body">
                    <form action="{{ route('documentation.update', encrypt($doc->id)) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label>{{ __('Title') }}</label>
                            <input type="text" value="{{ $doc->title }}" name="title" class="form-control"
                                placeholder="Enter title">
                            <x-feedback-alert :datas="['errors' => $errors, 'field' => 'title']" />
                        </div>
                        <div class="form-group">
                            <label>{{ __('Module Key') }}</label>
                            <input type="text" value="{{ $doc->key }}" name="key" class="form-control"
                                placeholder="Enter module key">
                            <x-feedback-alert :datas="['errors' => $errors, 'field' => 'key']" />
                        </div>
                        <div class="form-group">
                            <label>{{ __('Type') }}</label>
                            <select name="type" class="form-control no-select">
                                <option value="" selected hidden>{{ __('Select Type') }}</option>
                                <option value="create" {{ $doc->type == 'create' ? 'selected' : '' }}>{{ __('Create') }}
                                </option>
                                <option value="update" {{ $doc->type == 'update' ? 'selected' : '' }}>{{ __('Update') }}
                                </option>
                            </select>
                            <x-feedback-alert :datas="['errors' => $errors, 'field' => 'type']" />
                        </div>
                        <div class="form-group">
                            <label>{{ __('Documentation') }}</label>
                            <textarea name="documentation" class="form-control" placeholder="Enter documentation">{{ $doc->documentation }}</textarea>
                            <x-feedback-alert :datas="['errors' => $errors, 'field' => 'documentation']" />
                        </div>
                        <div class="form-group float-end">
                            <input type="submit" class="btn btn-primary" value="Update">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script src="{{ asset('ckEditor5/main.js') }}"></script>
@endpush

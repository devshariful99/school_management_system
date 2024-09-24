@extends('backend.admin.layouts.master', ['page_slug' => 'admin'])
@section('title', 'Edit Admin')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="cart-title">{{ __('Edit Admin') }}</h4>
                     @include('backend.admin.includes.button', [
                            'routeName' => 'am.admin.index',
                            'label' => 'Back',
                            'permissions'=>['admin-list','admin-delete','admin-status'],
                        ])
                </div>
                <div class="card-body">
                    <form action="{{ route('am.admin.update', $admin->id) }}" method="POST" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label>{{ __('Name') }}</label>
                            <input type="text" name="name" value="{{ $admin->name }}" class="form-control"
                                placeholder="Enter name">
                            @include('alerts.feedback', ['field' => 'name'])
                        </div>
                        <div class="form-group">
                            <label>{{ __('Role') }}</label>
                            <select name="role" class="form-control">
                                <option value="" selected hidden>{{__('Select Role')}}</option>
                                @foreach ($roles as $role)
                                    <option value="{{$role->id}}" {{$admin->role_id == $role->id ? 'selected' : ''}}>{{$role->name}}</option>
                                @endforeach
                            </select>
                            @include('alerts.feedback', ['field' => 'role'])
                        </div>
                        <div class="form-group">
                            <label>{{ __('Image') }}</label>
                            <input type="file" accept="image/*" name="image" class="form-control">
                            @include('alerts.feedback', ['field' => 'image'])
                        </div>
                        @if ($admin->image)
                            <img src="{{ asset('storage/' . $admin->image) }}" alt="" width="100" height="100">
                        @endif
                        <div class="form-group">
                            <label>{{ __('Email') }}</label>
                            <input type="text" name="email" value="{{ $admin->email }}" class="form-control"
                                placeholder="Enter email">
                            @include('alerts.feedback', ['field' => 'email'])
                        </div>
                        <div class="form-group">
                            <label>{{ __('Password') }}</label>
                            <input type="password" name="password" class="form-control" placeholder="Enter password">
                            @include('alerts.feedback', ['field' => 'password'])
                        </div>
                        <div class="form-group">
                            <label>{{ __('Confirm Password') }}</label>
                            <input type="password" name="password_confirmation" class="form-control"
                                placeholder="Enter confirm password">
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

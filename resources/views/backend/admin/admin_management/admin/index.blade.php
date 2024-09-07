@extends('backend.admin.layouts.master',['page_slug'=>'admin'])
@section('title','Admin List')
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="cart-title">Admin List</h4>
                    <a href="{{route('am.admin.create')}}" class="btn btn-sm btn-primary">Add New</a>
                </div>
                <div class="card-body">
                    <table class="table table-responsive table-striped">
                        <thead>
                            <tr>
                                <th>{{__('SL')}}</th>
                                <th>{{__('Name')}}</th>
                                <th>{{__('Email')}}</th>
                                <th>{{__('Created Date')}}</th>
                                <th>{{__('Updated Date')}}</th>
                                <th>{{__('Action')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($admins as $admin)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{ $admin->name }}</td>
                                    <td>{{ $admin->email }}</td>
                                    <td>{{ date('d M, Y', strtotime($admin->created_at)) }}</td>
                                    <td>{{ $admin->created_at != $admin->updated_at ? date('d M, Y', strtotime($admin->updated_at)) : 'NULL' }}</td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="javascript:void(0)" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="icon-options-vertical"></i>
                                            </a>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a href="" class="dropdown-item">{{__('Details')}}</a></li>
                                                <li><a href="" class="dropdown-item">{{__('Edit')}}</a></li>
                                                <li><a href="" class="dropdown-item">{{__('Delete')}}</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
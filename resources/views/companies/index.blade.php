@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-9">
        <p class="display-5">Companies</p>
    </div>
    <div class="col-md-3">
        <div class="h-100 pb-2 d-flex justify-content-end align-items-end">
            <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                <a href="{{ route('calls.index') }}" type="button"
                    class="btn btn-outline-secondary btn-sm">Calls</a>
                <a href="{{ route('companies.index') }}" type="button"
                    class="btn btn-secondary btn-sm">Companies</a>
            </div>
        </div>
    </div>
</div>
@if(Session::has('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ Session::get('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

@elseif(Session::has('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ Session::get('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif

@error('name')
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ $message }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@enderror
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-end align-items-center h-1 cursor-pointer">
            <a data-toggle="modal" data-target="#createModal">
                <svg width="1.2rem" height="1.2rem" viewBox="0 0 16 16" class="bi bi-plus-square" fill="currentColor">
                    <path fill-rule="evenodd"
                        d="M8 3.5a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-.5.5H4a.5.5 0 0 1 0-1h3.5V4a.5.5 0 0 1 .5-.5z" />
                    <path fill-rule="evenodd" d="M7.5 8a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1H8.5V12a.5.5 0 0 1-1 0V8z" />
                    <path fill-rule="evenodd"
                        d="M14 1H2a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zM2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z" />
                </svg>
            </a>
        </div>
    </div>
    <table class="card-table table table-hover table-bordered">
        <thead>
            <th>Name</th>
            <th width="10%">Actions</th>
        </thead>
        <tbody>
            @foreach($companies as $company)
                <tr>
                    <td>{{ $company->name }}</td>
                    <td>
                        <div class="options">
                            <div class="option-item">
                                <a data-toggle="modal" data-target="#editModal" data-name="{{ $company->name }}"
                                    data-id="{{ $company->id }}" class=" text-success">
                                    <i class="fa fa-pencil"></i>
                                </a>
                            </div>
                            <div class="option-item">
                                <a href="" class="text-danger" data-toggle="modal" data-target="#deleteModal"
                                    data-name="{{ $company->name }}" data-id="{{ $company->id }}">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endsection


@section('modal')
<div class="modal fade" id="createModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Company</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('companies.store') }}" id="add-form">
                    @csrf
                    <div class="form-group">
                        <label for="name" class="col-form-label font-weight-bold">Company Name:</label>
                        <input type="text" class="form-control" name="name">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button form="add-form" type="submit" class="btn btn-sm btn-success">Add Company</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="editModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Company</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('companies.update') }}" id="update-form">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="name" class="col-form-label font-weight-bold">Company Name:</label>
                        <input type="text" class="form-control" name="name">
                        <input type="hidden" class="form-control" name="company_id">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" form="update-form" class="btn btn-sm btn-success">Update Company</button>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Company</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you wanted to delete <span class="font-weight-bold" id="company_name"></span>?</p>
            </div>
            <div class="modal-footer">
                <form method="POST" action="{{ route('companies.destroy') }}" id="delete-form">
                    @csrf
                    @method('DELETE')
                    <div class="form-group">
                        <input type="hidden" class="form-control" name="company_id">
                    </div>
                </form>
                <button type="submit" form="delete-form" class="btn btn-sm btn-danger">Delete Company</button>
            </div>
        </div>
    </div>
</div>
@endsection

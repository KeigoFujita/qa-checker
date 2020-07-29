@extends('layouts.app')

@section('content')


@if($errors->any())
    @foreach($errors->all() as $error)
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ $error }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endforeach
@endif

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

@forelse($weekly_calls as $calls_per_day)

    {{-- Header --}}
    <div class="row">
        <div class="col-md-9">
            <p class="display-5">{{ $calls_per_day->date }}</p>
        </div>
        <div class="col-md-3">
            @if($loop->index == 0)
                <div class="h-100 pb-2 d-flex justify-content-end align-items-end">
                    <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                        <a href="{{ route('calls.index') }}" type="button"
                            class="btn btn-secondary btn-sm">Calls</a>
                        <a href="{{ route('companies.index') }}" type="button"
                            class="btn btn-outline-secondary btn-sm">Companies</a>
                    </div>
                </div>
            @endif
        </div>
    </div>


    {{-- Table --}}
    <div class="card mb-5">

        @if($calls_per_day->calls->count() > 0)
            @include('partials.calls-table-card-header')
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="card-table table table-hover mt-0 table-striped calls">
                        <thead>
                            <th width="30%">Company</th>
                            <th>Rating</th>
                            <th>Duration</th>
                            <th>USD</th>
                            <th>PHP</th>
                            <th width="5%"></th>
                        </thead>
                        <tbody>
                            @foreach($calls_per_day->calls as $call)
                                <tr>
                                    <td>{{ $call->company->name }}</td>
                                    <td>{{ $call->rating }}</td>
                                    <td>{{ $call->display_duration() }}</td>
                                    <td>${{ $call->amount_earned }}</td>
                                    <td>₱{{ $call->display_amount_earned_in_peso() }}</td>
                                    <td>
                                        <div class="dropleft">
                                            <a data-toggle="dropdown" class="cursor-pointer text-secondary">
                                                <svg width="1em" height="1em" viewBox="0 0 16 16"
                                                    class="bi bi-three-dots-vertical" fill="currentColor"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd"
                                                        d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z" />
                                                </svg>
                                            </a>
                                            <div class="dropdown-menu py-1">
                                                <a data-toggle="modal" data-target="#editCallModal"
                                                    data-id="{{ $call->id }}" data-rating="{{ $call->rating }}"
                                                    data-amount-earned="{{ $call->amount_earned }}"
                                                    data-duration="{{ $call->duration }}"
                                                    data-company-id="{{ $call->company->id }}">
                                                    <div class="d-flex align-items-center px-3 py-2 menu-item">
                                                        <svg width="1em" height="1em" viewBox="0 0 16 16"
                                                            class="bi bi-pencil mr-3" fill="currentColor"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd"
                                                                d="M11.293 1.293a1 1 0 0 1 1.414 0l2 2a1 1 0 0 1 0 1.414l-9 9a1 1 0 0 1-.39.242l-3 1a1 1 0 0 1-1.266-1.265l1-3a1 1 0 0 1 .242-.391l9-9zM12 2l2 2-9 9-3 1 1-3 9-9z" />
                                                            <path fill-rule="evenodd"
                                                                d="M12.146 6.354l-2.5-2.5.708-.708 2.5 2.5-.707.708zM3 10v.5a.5.5 0 0 0 .5.5H4v.5a.5.5 0 0 0 .5.5H5v.5a.5.5 0 0 0 .5.5H6v-1.5a.5.5 0 0 0-.5-.5H5v-.5a.5.5 0 0 0-.5-.5H3z" />
                                                        </svg>
                                                        <p class="mb-0" style="font-size: 0.8rem; line-height: 0.8rem;">
                                                            Edit</p>
                                                    </div>
                                                </a>
                                                <div class="d-flex align-items-center px-3 py-2 border-top menu-item">
                                                    <svg width="1em" height="1em" viewBox="0 0 16 16"
                                                        class="bi bi-trash mr-3" fill="currentColor"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                                                        <path fill-rule="evenodd"
                                                            d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4L4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
                                                    </svg>
                                                    <p class="mb-0" style="font-size: 0.8rem; line-height: 0.8rem;">
                                                        Delete</p>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                            <tr>
                                <td>Summary</td>
                                <td>{{ $items['summary']['rating'] }}
                                </td>
                                <td>{{ $items['summary']['total_duration'] }}
                                </td>
                                <td>${{ $items['summary']['total_amount_earned'] }}
                                </td>
                                <td>₱{{ $items['summary']['total_amount_earned_in_peso'] }}
                                </td>
                                <td></td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        @else
            @include('partials.calls-table-card-header')
            <div class="card-body p-0">
                <div class="table-resposive">
                    <table class="card-table table table-hover mt-0 table-striped calls">
                        <thead>
                            <th width="30%">Company</th>
                            <th>Rating</th>
                            <th>Duration</th>
                            <th>USD</th>
                            <th>PHP</th>
                            <th width="5%"></th>
                        </thead>
                        <tbody>
                            <tr></tr>
                        </tbody>
                    </table>
                </div>
                <div style="height: 20rem" class="d-flex align-items-center justify-content-center">
                    <p class="mb-0">No calls recorded</p>
                </div>
            </div>
        @endif
    </div>
@endforeach
@endsection

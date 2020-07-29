@extends('layouts.app',['sum' => $sum])
@section('content')

@include('components.alerts')
@include('partials.tab-bar')
@foreach($weekly_calls as $calls_per_day)
    {{-- Header --}}
    <p class="display-5">{{ $calls_per_day['date'] }}</p>
    {{-- Table --}}
    <div class="card mb-5">

        @if(count($calls_per_day['calls']) > 0)
            @include('partials.calls-table-card-header')
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="card-table table table-hover mt-0 calls">
                        <thead>
                            <th width="30%">Call ID</th>
                            <th>Rating</th>
                            <th>Duration</th>
                            <th>USD</th>
                            <th>Owner</th>
                            <th width="5%"></th>
                        </thead>
                        <tbody>

                            @foreach($calls_per_day['calls'] as $call)
                                <tr class="@include('calls.etc.row-left-border',['rating'=>$call->quality_rating])">
                                    <td> {{ $call->call_id }} </td>
                                    <td> {{ $call->quality_rating }} </td>
                                    <td> {{ $call->audio_minutes }} </td>
                                    <td> {{ '$'.to_money_format($call->amount_earned) }}</td>
                                    <td> {{ $call->owner }} </td>
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
                                            @include('calls.etc.dropdown-menu')
                                        </div>
                                    </td>
                                </tr>
                            @endforeach

                            <tr>
                                <td>Summary</td>
                                <td>
                                    {{ number_format($calls_per_day['average_rating'],1) }}
                                </td>
                                <td></td>
                                <td>
                                    {{ '$' . to_money_format($calls_per_day['sum']) }}
                                </td>
                                <td>
                                    {{ '₱' . to_money_format(convertToPeso($calls_per_day['sum'])) }}
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
                <div class="table-responsive">
                    <table class="card-table table table-hover mt-0 calls">
                        <thead>
                            <th width="30%">Company</th>
                            <th>Rating</th>
                            <th>Duration</th>
                            <th>USD</th>
                        </thead>
                    </table>
                </div>
                <div style="height: 20rem" class="d-flex align-items-center justify-content-center">
                    <p class="mb-0">No calls recorded</p>
                </div>
            </div>
        @endif
    </div>
    {{ -- --}}
@endforeach
@endsection

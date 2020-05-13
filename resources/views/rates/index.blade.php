@extends('layouts.app')

@section('content')
    <div class="container">
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <p class="float-left">Rates</p>
                        <a href="{{ route('rates.create') }}" class="btn btn-primary float-right">Add Rates</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center py-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Today - {{ date('Y-m-d') }}
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Currency</th>
                                <th>Rate</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($todayCurrencies as $currency)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $currency->name }}</td>
                                    <td>{{ optional($currency->rate)->value ?? '-'}}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row justify-content-center py-4">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Past Days
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                @foreach($currencies as $currency)
                                    <th>{{ $currency->name }}</th>
                                @endforeach
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($rates as $date => $rate)
                                <tr>
                                    <td>{{ $date }}</td>
                                    @foreach($currencies as $currency)
                                        <th>
                                            {{
                                                $rate->first(function($item) use ($currency){
                                                    return $item->currency_id == $currency->id;
                                                })->value ?? '-'
                                            }}
                                        </th>
                                    @endforeach
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

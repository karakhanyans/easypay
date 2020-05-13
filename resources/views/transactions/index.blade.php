@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <p class="float-left">Transactions</p>
                        <div class="float-right">
                            <form action="" method="get">
                                <div class="form-group row">
                                    <input type="date" name="from"
                                           value="{{request('from') ?? now()->subMonth()->format('Y-m-d')}}">
                                    <input type="date"  name="to"
                                           value="{{ request('to') ?? now()->addDay()->format('Y-m-d')}}">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        {{ __('Filter') }}
                                    </button>
                                </div>
                            </form>
                            <a href="{{ route('transactions.download') }}" class="btn btn-success float-right">CSV</a>
                        </div>
                    </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Type</th>
                                <th>To/From</th>
                                <th>Amount</th>
                                <th>Currency</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($transactions as $transaction)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $transaction->created_at->format('Y-m-d') }}</td>
                                    <td>{{ ucfirst(\App\Models\Transaction::$types[$transaction->type]) }}</td>
                                    <td>{{ $transaction->causedBy->id === \Illuminate\Support\Facades\Auth::id() ? 'By Yourself' :$transaction->causedBy->name }}</td>
                                    <td>{{ $transaction->amount }}</td>
                                    <td>{{ $transaction->wallet->currency->name }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer">
                        @foreach($sum as $key => $value)
                            <div>Total {{ $key }}:</div>
                            <ul>
                                @foreach($value as $item)
                                    @foreach($item as $currency => $sum)
                                        <li>
                                            From {{ $currency }} wallet: ${{ $sum }}
                                        </li>
                                    @endforeach
                                @endforeach
                            </ul>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

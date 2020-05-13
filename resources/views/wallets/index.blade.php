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
                        <p class="float-left">Wallets</p>
                        <a href="{{ route('wallets.create') }}" class="btn btn-primary float-right">Add Wallet</a>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Currency</th>
                                <th>Balance</th>
                                <th>Actions</th>
                                <th>Transaction</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($wallets as $wallet)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $wallet->name }}</td>
                                    <td>{{ optional($wallet->currency)->name ?? '-'}}</td>
                                    <td>{{ number_format($wallet->balance, 2) }}</td>
                                    <td><a href="{{ route('wallets.edit', $wallet) }}" class="btn btn-success">Fill Balance</a></td>
                                    <td><a href="{{ route('wallets.transaction', $wallet) }}" class="btn btn-success">Send Money</a></td>
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

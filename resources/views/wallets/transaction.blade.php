@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <p class="float-left">Send money from current balance ({{ $wallet->currency->name }}
                            : {{ number_format($wallet->balance, 2) }})</p>
                        <a href="{{ route('wallets.index') }}" class="btn btn-primary float-right"> < Back</a>
                    </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <form action="{{ route('wallets.transaction.store', $wallet) }}" method="POST">
                            @csrf
                            @method('POST')
                            <div class="form-group row">
                                <label for="wallet_id"
                                       class="col-md-4 col-form-label text-md-right">{{ __('User Wallet') }}</label>

                                <div class="col-md-6">
                                    <select class="form-control" name="wallet_id" id="wallet_id">
                                        @foreach($users as $user)
                                            <optgroup label="{{ $user->name }}">
                                                @foreach($user->wallets as $wallet)
                                                    <option value="{{ $wallet->id }}">{{ $user->name }} - {{ $wallet->currency->name }}</option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                    @error('wallet_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="amount"
                                       class="col-md-4 col-form-label text-md-right">{{ __('Amount to send') }}</label>

                                <div class="col-md-6">
                                    <input id="amount" type="number" step="0.01"
                                           class="form-control @error('amount') is-invalid @enderror" name="amount"
                                           value="{{ old('amount') }}" required>

                                    @error('amount')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Add') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

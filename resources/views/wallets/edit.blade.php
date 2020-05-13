@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <p class="float-left">Fill Wallet Balance: Current Balance ({{ $wallet->currency->name }}: {{ number_format($wallet->balance, 2) }})</p>
                        <a href="{{ route('wallets.index') }}" class="btn btn-primary float-right"> < Back</a>
                    </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <form action="{{ route('wallets.update', $wallet) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="form-group row">
                                <label for="balance"
                                       class="col-md-4 col-form-label text-md-right">{{ __('Add to the Balance') }}</label>

                                <div class="col-md-6">
                                    <input id="balance" type="number" step="0.01"
                                           class="form-control @error('balance') is-invalid @enderror" name="balance"
                                           value="{{ old('balance') }}" required>

                                    @error('balance')
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

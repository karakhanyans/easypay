@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <p class="float-left">Create Currency Rates to USD</p>
                        <a href="{{ route('rates.index') }}" class="btn btn-primary float-right"> < Back</a>
                    </div>

                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        <form action="{{ route('rates.store') }}" method="POST">
                            @csrf
                            <div class="form-group row">
                                <label for="currency_id"
                                       class="col-md-4 col-form-label text-md-right">{{ __('Currency') }}</label>

                                <div class="col-md-6">
                                    <select class="form-control" name="currency_id" id="currency_id">
                                        @foreach($currencies as $currency)
                                            <option value="{{ $currency->id }}">{{ $currency->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('currency_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="value"
                                       class="col-md-4 col-form-label text-md-right">{{ __('Value to USD') }}</label>

                                <div class="col-md-6">
                                    <input id="value" type="number" step="0.0001"
                                           class="form-control @error('value') is-invalid @enderror" name="value"
                                           value="{{ old('value') }}" required>

                                    @error('value')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="date" class="col-md-4 col-form-label text-md-right">{{ __('Date') }}</label>

                                <div class="col-md-6">
                                    <input id="date" type="date"
                                           class="form-control @error('date') is-invalid @enderror" name="date"
                                           value="{{ old('date') ?? date('Y-m-d')}}" required>

                                    @error('date')
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

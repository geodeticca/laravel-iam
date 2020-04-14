@extends('layouts.app')

@section('content')
    <h1>{{ __('iam::account.account') }}</h1>

    @include('assistant::report.all')

    <form method="post" action="{{ route('account') }}">
        @csrf

        <div class="form-group row">
            <label for="forename" class="col-md-4 col-form-label text-md-right">{{ __('iam::account.forename') }}</label>
            <div class="col-md-6">
                <input id="forename" type="text" class="form-control{{ $errors->has('forename') ? ' is-invalid' : '' }}" name="forename" value="{{ old('forename', $user->forename) }}" required autofocus>

                @include('assistant::validation.field', ['field' => 'forename'])
            </div>
        </div>

        <div class="form-group row">
            <label for="surname" class="col-md-4 col-form-label text-md-right">{{ __('iam::account.surname') }}</label>
            <div class="col-md-6">
                <input id="surname" type="text" class="form-control{{ $errors->has('surname') ? ' is-invalid' : '' }}" name="surname" value="{{ old('surname', $user->surname) }}" required>

                @include('assistant::validation.field', ['field' => 'surname'])
            </div>
        </div>

        <div class="form-group row">
            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('iam::account.email') }}</label>
            <div class="col-md-6">
                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email', $user->email) }}" required>

                @include('assistant::validation.field', ['field' => 'email'])
            </div>
        </div>

        <div class="form-group row">
            <label for="phone" class="col-md-4 col-form-label text-md-right">{{ __('iam::account.phone') }}</label>
            <div class="col-md-6">
                <input id="phone" type="text" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" name="phone" value="{{ old('phone', $user->phone) }}">

                @include('assistant::validation.field', ['field' => 'phone'])
            </div>
        </div>

        <hr />

        <div class="form-group row">
            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('iam::account.password') }}</label>

            <div class="col-md-6">
                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password">

                @include('assistant::validation.field', ['field' => 'password'])
            </div>
        </div>

        <div class="form-group row">
            <label for="password_confirmation" class="col-md-4 col-form-label text-md-right">{{ __('iam::account.password_confirmation') }}</label>

            <div class="col-md-6">
                <input id="password_confirmation" type="password" class="form-control" name="password_confirmation">
            </div>
        </div>

        @if($user->hasApiToken())
            <div class="form-group row">
                <label for="api_token" class="col-md-4 col-form-label text-md-right">{{ __('iam::account.api_token') }}</label>

                <div class="col-md-6">
                    <input id="api_token" type="text" class="form-control" name="api_token" value="{{ $user->api_token }}" readonly>
                </div>
            </div>
        @endif

        <div class="form-group row mb-0">
            <div class="col-md-6 offset-md-4">
                <button type="submit" class="btn btn-success">
                    <span class="material-icons">save</span>
                    {{ __('assistant::general.save') }}
                </button>
            </div>
        </div>
    </form>
@endsection

@extends('layouts.app')

@section('content')

<h2 class="subtitle">
{{ __('Reset Password') }}
</h2>

<article class="message">
    <div class="message-body">

    <form method="POST" action="{{ route('password.request') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="field">
            <label for="email" class="label">{{ __('E-Mail Address') }}</label>

            <div class="control">
                <input id="email" type="email" class="input form-control{{ $errors->has('email') ? ' is-danger' : '' }}" name="email" value="{{ $email or old('email') }}" required autofocus>

                @if ($errors->has('email'))
                    <p class="help">
                        <strong>{{ $errors->first('email') }}</strong>
                    </p>
                @endif
            </div>
        </div>

        <div class="field">
            <label for="password" class="label">{{ __('Password') }}</label>

            <div class="control">
                <input id="password" type="password" class="input form-control{{ $errors->has('password') ? ' is-danger' : '' }}" name="password" required>

                @if ($errors->has('password'))
                    <p class="help">
                        <strong>{{ $errors->first('password') }}</strong>
                    </p>
                @endif
            </div>
        </div>

        <div class="field">
            <label for="password-confirm" class="label">{{ __('Confirm Password') }}</label>
            <div class="control">
                <input id="password-confirm" type="password" class="input form-control{{ $errors->has('password_confirmation') ? ' is-danger' : '' }}" name="password_confirmation" required>

                @if ($errors->has('password_confirmation'))
                    <p class="help">
                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                    </p>
                @endif
            </div>
        </div>

        <div class="field">
            <div class="control">
                <button type="submit" class="button is-primary">
                    {{ __('Reset Password') }}
                </button>
            </div>
        </div>
    </form>

    </div>
</article>

@endsection

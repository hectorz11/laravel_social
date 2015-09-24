@extends('layouts.main')
 
@section('head')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('/assets/css/register.css') }}">
@stop
 
@section('content')
 
    {!! Form::open(['route' => 'auth.register-post', 'class' => 'form-signin' ] ) !!}
 
        <h2 class="form-signin-heading">Please register</h2>
 
        <label for="inputEmail" class="sr-only">Email address</label>
        {!! Form::email('email', null, ['class' => 'form-control', 'placeholder' => 'Email address', 'autofocus', 'id' => 'inputEmail' ]) !!}
        @if( $errors->has('email') )
            <div class="alert alert-danger">
                @foreach($errors->get('email') as $error)
                    * {{$error}}</br>
                @endforeach
            </div>
        @endif
        <label for="inputFirstName" class="sr-only">First name</label>
        {!! Form::text('first_name', null, ['class' => 'form-control', 'placeholder' => 'First name', 'id' => 'inputFirstName' ]) !!}
        @if( $errors->has('first_name') )
            <div class="alert alert-danger">
                @foreach($errors->get('first_name') as $error)
                    * {{$error}}</br>
                @endforeach
            </div>
        @endif
        <label for="inputLastName" class="sr-only">Last name</label>
        {!! Form::text('last_name', null, ['class' => 'form-control', 'placeholder' => 'Last name', 'id' => 'inputLastName' ]) !!}
        @if( $errors->has('last_name') )
            <div class="alert alert-danger">
                @foreach($errors->get('last_name') as $error)
                    * {{$error}}</br>
                @endforeach
            </div>
        @endif
        <label for="inputPassword" class="sr-only">Password</label>
        {!! Form::password('password', ['class' => 'form-control', 'placeholder' => 'Password', 'id' => 'inputPassword' ]) !!}
        @if( $errors->has('password') )
            <div class="alert alert-danger">
                @foreach($errors->get('password') as $error)
                    * {{$error}}</br>
                @endforeach
            </div>
        @endif
        <label for="inputPasswordConfirm" class="sr-only">Confirm Password</label>
        {!! Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => 'Password confirmation', 'id' => 'inputPasswordConfirm' ]) !!}
        @if( $errors->has('password_confirmation') )
            <div class="alert alert-danger">
                @foreach($errors->get('password_confirmation') as $error)
                    * {{$error}}</br>
                @endforeach
            </div>
        @endif
 
 
        <button class="btn btn-lg btn-primary btn-block" type="submit">Register</button>
 
        <p class="or-social">Or Use Social Login</p>
 
        <a class="btn btn-lg btn-primary btn-block facebook" type="submit">Facebook</a>
        <a class="btn btn-lg btn-primary btn-block twitter" type="submit">Twitter</a>
 
    {!! Form::close() !!}
 
@stop
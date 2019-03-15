@extends('layouts.app')
@section('title', 'Hint')

@section('content')
<div class="panel panel-default">
    <div class="panel-heading">Hint</div>
    <div class="panel-body text-center">
        <h1>Please verify your Email</h1>
        <a class="btn btn-primary" href="{{ route('email_verification.send') }}">Re-send Verification Email</a>
    </div>
</div>
@endsection
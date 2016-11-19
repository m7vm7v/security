@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">My account</div>                
                <div class="panel-body">

                    <form method="POST" action="/user/settings" class="form-horizontal">

                        {{ csrf_field() }}

                        {{ method_field('PATCH') }}

                        <div class="form-group{{ $errors->first('name') ? ' has-error' : '' }}">
                            <label for="name" class="col-md-4 control-label">Name</label>

                            <div class="col-md-6">
                                <input id="name" type="name" class="form-control" name="name" value="{{ old('name') ?? $user->name }}" required>

                                @if ($errors->first('name'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('name') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->first('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') ?? $user->email }}" required>

                                @if ($errors->first('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email" class="col-md-4 control-label">Security level</label>

                            <div class="btn-group" role="group" style="margin-left: 15px">
                                <button type="button" class="btn btn-default">Pass or Email</button> <!-- Security level 1 -->
                                <button type="button" class="btn btn-default">Email</button> <!-- Security level 2 -->
                                <button type="button" class="btn btn-default">Pass</button> <!-- Security level 3 -->
                                <button type="button" class="btn btn-default">Email then pass</button> <!-- Security level 4 & 5 -->
                                <button type="button" class="btn btn-default">PIN</button> <!-- Security level 6 -->
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary center-block">Update account.</button>
                        </div>

                    </form>

                </div>
            </div>
        </div>
        @endsection

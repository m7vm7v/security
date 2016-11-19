@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">PIN</div>

                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/password/pin') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->any() ? ' has-error' : '' }}">
                            <label for="pin" class="col-md-4 control-label">PIN</label>

                            <div class="col-md-6">
                                <input id="pin" type="number" class="form-control" name="pin" value="{{ $pin or old('pin') }}"  autofocus required>

                                @if($errors->any())
                                    <span class="help-block">
                                        <strong>{{ $errors->first() }}</strong>
                                    </span>
                                @endif
                                
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Activate my account
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

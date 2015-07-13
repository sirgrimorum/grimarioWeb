@extends("layouts.principal")
@section("contenido")

<h1>{{ Lang::get("user.titulos.nuevo_password") }}</h3>
<div class='container'>
    {{ HTML::ul($errors->all()) }}
    {{ Form::horizontal(array('url' => action("UsersController@postChangePassword"),'id'=>'form_password')) }}
    <input type="hidden" name="rcode" value="{{ $rcode }}"/>
    <input type="hidden" name="id" value="{{ $user->id }}"/>
    <div class="form-group">
        <label for="passwordEmail" class="control-label">{{ Lang::get('user.labels.email') }}</label>
        <input type="email" class="form-control" id="passwordEmail" readonly="readonly" value="{{ $user->email }}">
    </div>
    <div class="form-group">
        <label for="passwordPassword" class="control-label">{{ Lang::get('user.labels.newpassword') }}</label>
        <input type="password" name="password" class="form-control" id="passwordPassword" placeholder="{{ Lang::get('user.placeholders.newpassword') }}">
        <span class="help-inline" id="passwordPassword_help"></span>
    </div>
    <div class="form-group">
        <label for="passwordPassword2" class="control-label">{{ Lang::get('user.labels.newpassword2') }}</label>
        <input type="password" name="password2" class="form-control" id="passwordPassword" placeholder="{{ Lang::get('user.placeholders.newpassword2') }}">
        <span class="help-inline" id="passwordPassword_help"></span>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-default">{{ Lang::get('user.labels.cambiar_clave') }}</button>
    </div>
    {{ Form::close() }}
</div>

@stop

@section("selfjs")
<script>
    $(document).ready(function() {
    });
</script>
@stop

@section("selfcss")
<!--{{ HTML::style("css/acerca.css") }} -->
@stop
@extends("layouts.principal")
@section("contenido")

<h1>{{ Lang::get("user.titulos.login") }}</h3>
<div class="login_menu">
    {{ Form::horizontal(array('url' => action("UsersController@postLogin"),'id'=>'form_login_plantilla')) }}
    <div class="form-group">
        <label for="loginPlaEmail" class="control-label">{{ Lang::get('user.labels.email') }}</label>
        <input type="email" name="email" class="form-control" id="loginPlaEmail" placeholder="{{ Lang::get('user.descriptions.email') }}">
        <span class="help-inline" id="loginPlaEmail_help"></span>
    </div>
    <div class="form-group">
        <label for="loginPlaPassword" class="control-label">{{ Lang::get('user.labels.password') }}</label>
        <input type="password" name="password" class="form-control" id="loginPlaPassword" placeholder="{{ Lang::get('user.descriptions.password') }}">
        <span class="help-inline" id="loginPlaPassword_help"></span>
    </div>
    <div class="form-group">
        <div class="checkbox">
            <label>
                <input type="checkbox" name='loginRemember' value="remember"> {{ Lang::get('user.labels.remember') }}
            </label>
        </div>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-default" id="loginPla">{{ Lang::get('user.labels.ingresar') }}</button>
    </div>
    {{ Form::close() }}
</div>
<div>
    <a href='{{ action('OauthController@getLinkedincallback') }}'>
        {{ Lang::get("user.labels.linkedin") }}
    </a>
</div>
<div>
    <a href='{{ action('OauthController@getGooglecallback') }}'>
        {{ Lang::get("user.labels.google") }}
    </a>
</div>

@stop

@section("selfjs")
<script>
    $(document).ready(function() {
        $("#form_login_plantilla").submit(function(event) {
            event.preventDefault();
            $.ajax({
                type: 'POST',
                url: $("#form_login_plantilla").attr("action"),
                data: $("#form_login_plantilla").serialize(),
                beforeSend: function(xhr) {

                }
            }).done(function(datos) {
                if (datos.result == 1) {
                    if (datos.message.email) {
                        if (datos.message.email.length > 0) {
                            $("#loginPlaEmail").parent().addClass('has-error');
                            $("#loginPlaEmail_help").html(datos.message.email.toString());
                        } else {
                            $("#loginPlaEmail").parent().removeClass('has-error');
                            $("#loginPlaEmail_help").html("");
                        }
                    } else {
                        $("#loginPlaEmail").parent().removeClass('has-error');
                        $("#loginPlaEmail_help").html("");
                    }
                    if (datos.message.password) {
                        if (datos.message.password.length > 0) {
                            $("#loginPlaPassword").parent().addClass('has-error');
                            $("#loginPlaPassword_help").html(datos.message.password.toString());
                        } else {
                            $("#loginPlaPassword").parent().removeClass('has-error');
                            $("#loginPlaPassword_help").html("");
                        }
                    } else {
                        $("#loginPlaPassword").parent().removeClass('has-error');
                        $("#loginPlaPassword_help").html("");
                    }
                } else if (datos.result == 2) {
                    $("#loginPlaPassword").parent().removeClass('has-error');
                    $("#loginPlaPassword_help").html("");
                    $("#loginPlaEmail").parent().addClass('has-error');
                    $("#loginPlaEmail_help").html(datos.message);
                } else if (datos.result == 3) {
                    $("#loginPlaPassword").parent().addClass('has-error');
                    $("#loginPlaPassword_help").html(datos.message);
                    $("#loginPlaEmail").parent().removeClass('has-error');
                    $("#loginPlaEmail_help").html("");
                } else {
                    if (document.referrer) {
                        window.history.back();
                    } else {
                        window.location.href ="{{ action('HomeController@showWelcome') }}";
                    }
                }
            });
        });
    });
</script>
@stop

@section("selfcss")
<!--{{ HTML::style("css/acerca.css") }} -->
@stop
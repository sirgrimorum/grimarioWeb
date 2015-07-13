<div class="login_menu">
    {{ Form::horizontal(array('url' => action("UsersController@postLogin"),'id'=>'form_login')) }}
    <div class="form-group">
        <label for="loginEmail" class="control-label">{{ Lang::get('user.labels.email') }}</label>
        <input type="email" name="email" class="form-control" id="loginEmail" placeholder="{{ Lang::get('user.descriptions.email') }}">
        <span class="help-inline" id="loginEmail_help"></span>
    </div>
    <div class="form-group">
        <label for="loginPassword" class="control-label">{{ Lang::get('user.labels.password') }}</label>
        <input type="password" name="password" class="form-control" id="loginPassword" placeholder="{{ Lang::get('user.descriptions.password') }}">
        <span class="help-inline" id="loginPassword_help"></span>
    </div>
    <div class="form-group">
        <div class="checkbox">
            <label>
                <input type="checkbox" name='loginRemember' value="remember"> {{ Lang::get('user.labels.remember') }}
            </label>
        </div>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-default" id="login">{{ Lang::get('user.labels.ingresar') }}</button>
    </div>
    {{ Form::close() }}
</div>

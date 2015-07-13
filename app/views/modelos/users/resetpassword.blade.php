<div class="reset_menu">
    {{ Form::horizontal(array('url' => action("UsersController@postResetPassword"),'id'=>'form_reset')) }}
    <div class="form-group">
        <label for="resetEmail" class="control-label">{{ Lang::get('user.labels.email') }}</label>
        <input type="email" name="email" class="form-control" id="resetEmail" placeholder="{{ Lang::get('user.descriptions.email') }}">
        <span class="help-inline" id="resetEmail_help"></span>
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-default" id="reset">{{ Lang::get('user.labels.reset') }}</button>
    </div>
    {{ Form::close() }}
</div>

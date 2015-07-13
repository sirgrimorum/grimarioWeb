$(document).ready(function() {
    $(".input-group-addon").parent('div.input-group').css("max-width", "200px");
    $("#form_login").submit(function(event) {
        event.preventDefault();
        $.ajax({
            type: 'POST',
            url: $("#form_login").attr("action"),
            data: $("#form_login").serialize(),
            beforeSend: function(xhr) {

            }
        }).done(function(datos) {
            if (datos.result == 1) {
                if (datos.message.email) {
                    if (datos.message.email.length > 0) {
                        $("#loginEmail").parent().addClass('has-error');
                        $("#loginEmail_help").html(datos.message.email.toString());
                    } else {
                        $("#loginEmail").parent().removeClass('has-error');
                        $("#loginEmail_help").html("");
                    }
                } else {
                    $("#loginEmail").parent().removeClass('has-error');
                    $("#loginEmail_help").html("");
                }
                if (datos.message.password) {
                    if (datos.message.password.length > 0) {
                        $("#loginPassword").parent().addClass('has-error');
                        $("#loginPassword_help").html(datos.message.password.toString());
                    } else {
                        $("#loginPassword").parent().removeClass('has-error');
                        $("#loginPassword_help").html("");
                    }
                } else {
                    $("#loginPassword").parent().removeClass('has-error');
                    $("#loginPassword_help").html("");
                }
            } else if (datos.result == 2) {
                $("#loginPassword").parent().removeClass('has-error');
                $("#loginPassword_help").html("");
                $("#loginEmail").parent().addClass('has-error');
                $("#loginEmail_help").html(datos.message);
            } else if (datos.result == 3) {
                $("#loginPassword").parent().addClass('has-error');
                $("#loginPassword_help").html(datos.message);
                $("#loginEmail").parent().removeClass('has-error');
                $("#loginEmail_help").html("");
            } else {
                $("#loginEmail").parent().removeClass('has-error');
                $("#loginEmail_help").html("");
                $("#loginPassword").parent().removeClass('has-error');
                $("#loginPassword_help").html("");
                alert(datos.message);
            }
        });
    });
    $('a[href="#reset"]').click(function(event){
        event.preventDefault();
        $(".reset_menu").toggle();
        event.stopPropagation();
    });
    $("#form_reset").submit(function(event) {
        event.preventDefault();
        $.ajax({
            type: 'POST',
            url: $("#form_reset").attr("action"),
            data: $("#form_reset").serialize(),
            beforeSend: function(xhr) {

            }
        }).done(function(datos) {
            if (datos.result == 1) {
                if (datos.message.email) {
                    if (datos.message.email.length > 0) {
                        $("#resetEmail").parent().addClass('has-error');
                        $("#resetEmail_help").html(datos.message.email.toString());
                    } else {
                        $("#resetEmail").parent().removeClass('has-error');
                        $("#resetEmail_help").html("");
                    }
                } else {
                    $("#resetEmail").parent().removeClass('has-error');
                    $("#resetEmail_help").html("");
                }
            } else if (datos.result == 2) {
                $("#resetEmail").parent().addClass('has-error');
                $("#resetEmail_help").html(datos.message);
            } else {
                $("#resetEmail").parent().removeClass('has-error');
                $("#resetEmail_help").html("");
                alert(datos.message);
            }
        });
    });
});

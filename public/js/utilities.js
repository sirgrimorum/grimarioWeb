function compartirFacebook(url, titulo,  desc, callback) {
    if (url.indexOf("ttp://") < 0) {
        url = BASE_URL + url;
    }
    var codigo = 'http://www.facebook.com/dialog/feed?app_id=[FACEBOOK_APP_ID]' +
        '&link=[FULLY_QUALIFIED_LINK_TO_SHARE_CONTENT]' +
        '&picture=[LINK_TO_IMAGE]' +
        '&name=' + encodeURIComponent(titulo) +
        '&caption=' + encodeURIComponent(url) +
        '&description=' + encodeURIComponent(desc) +
        '&redirect_uri=' + FBVars.baseURL + callback +
        '&display=popup';
    //window.open('http://www.facebook.com/sharer.php?u='+encodeURIComponent(url)+'&s='+encodeURIComponent(desc)+'&t='+encodeURIComponent(desc),'sharer','toolbar=0,status=0,width=800,height=600');
    //window.open('http://www.facebook.com/sharer.php?u=' + encodeURIComponent(url) + '&p[url]=' + encodeURIComponent(url) + '&s=100&p[title]=' + encodeURIComponent(desc) + '&p[summary]=' + encodeURIComponent(desc), 'sharer', 'toolbar=0,status=0,width=800,height=600');
    window.open(codigo, 'sharer', 'toolbar=0,status=0,width=800,height=600');
    return false;
}


function redirectPost(location, args) {
    var form = '';
    $.each(args, function(key, value) {
        form += '<input type="hidden" name="' + key + '" value="' + value + '">';
    });
    $('<form action="' + location + '" method="POST">' + form + '</form>').appendTo('body').submit();
}

function alerta(mensaje,titulo){
    if(typeof titulo === "undefined") {
        titulo = tituloMensajes;
    }
    $("#modal_mensaje_body").html(mensaje);
    $("#modal_mensaje_label").html(titulo);
    $('#modal_mensaje').modal('show');
}

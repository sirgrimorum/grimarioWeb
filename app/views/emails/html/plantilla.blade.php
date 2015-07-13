<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
    </head>
    <body bgcolor="#ffffff" text="#666666">
        <div>
            <img src="{{ $message->embed(asset("images/img/GrimorumB.png")) }}" style="width:50px;text-align:left;" />
            <h2>{{ Lang::get("email.textos.grimario") }}</h2>
        </div>
        <h1>
            @yield("titulo")
        </h1>
        @yield("contenido")
        <div class="moz-signature">
            @section("firma")
            <i>
                <br>
                <br>
                {{ Lang::get("email.textos.cierre") }}<br>
                <br>
                <a href='http://www.grimorum.com'> 
                    {{ Lang::get("email.textos.de") }}
                </a>
                <br>
            </i>
            @show
        </div>
    </body>
</html>
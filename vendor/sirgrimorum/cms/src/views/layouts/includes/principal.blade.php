<!DOCTYPE html>
<html lang='es'>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8">
        <!-- Titulo del sitio -->
        <title>{{ Lang::get('principal.labels.titulo_home') }}</title>
        <!-- Icono del sitio -->
        <link rel="shortcut icon" href="{{asset('images/img/sirgrimorum.ico')}}">
        <!-- Metas adicionales -->
        <meta name="author" content="{{ Lang::get('principal.metadata.author') }}">
        <meta name="title" content="{{ Lang::get('principal.metadata.title') }}">
        <meta name="description" content="{{ Lang::get('principal.metadata.description') }}">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <!-- Incluye los correspondientes -->
        @include("cms::layouts.includes.mainstyle")
        <!-- Campo definido para incluir estilos especificos en las vistas que lo requieran -->
        @yield("selfcss")
    </head>

    <body>
        @include("cms::layouts.includes.menu")
        <!-- Contenido de la pagina -->
        <div class="container" style='margin-top: 50px;'>
            <div id="main">
                @yield("contenido")
            </div>
            @include("cms::layouts.includes.footer")
        </div>


        <!-- Incluye los javascript correspondientes -->
        @include("cms::layouts.includes.mainjs")
        <!-- Campo definido para incluir los javascript especificos en las vistas que lo requieran -->
        @yield("selfjs")    
        @yield("modales")
    </body>
</html>


<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ URL::route('admin_translations_home') }}">{{ Lang::get('cms::crud.layout.administrador') }}</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                @foreach (Config::get('cms::admin_routes') as $ruta=>$datos)
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">{{ ucfirst($datos['plural']) }} <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li><a href="{{ URL::to(Config::get('cms::admin_prefix') . "/" . $datos['plural']) }}">{{ Lang::get('cms::crud.layout.ver') }}</a></li>
                        <li class="divider"></li>
                        <li><a href="{{ URL::to(Config::get('cms::admin_prefix') . "/" . $datos['plural'] .'/create') }}">{{ Lang::get('cms::crud.layout.crear') }}</a></li>
                    </ul>
                </li>
                @endforeach
                @yield("menuobj")
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">{{ Lang::get('cms::crud.layout.idioma') }} <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        @foreach (Config::get('cms::list_locales') as $local)
                        <li><a href="{{ URL::route('admin_translations_relocate') }}/{{ $local }}">{{ $local }}</a></li>
                        @endforeach
                    </ul>
                </li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>


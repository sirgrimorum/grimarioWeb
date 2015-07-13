
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#menu_principal">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ URL::to('/') }}">{{ Lang::get('principal.labels.titulo_home') }}</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="menu_principal">
            <ul class="nav navbar-nav">
                @foreach (Lang::get('menu.izquierdo') as $nombre=>$ruta)
                @if (is_array($ruta))
                    <?php
                    $mostrar = true;
                    if (isset($ruta['logedin'])) {
                        if ($ruta['logedin'] === true) {
                            $mostrar = Sentry::check();
                            if ($mostrar){
                                $nombre = str_replace("{first_name}",Sentry::getUser()->first_name,$nombre);
                                $nombre = str_replace("{picture}",Sentry::getUser()->picture,$nombre);
                                $nombre = str_replace("{name}",Sentry::getUser()->name,$nombre);
                            }
                        } elseif ($ruta['logedin'] === false) {
                            $mostrar = ! Sentry::check();
                        }
                    }
                    ?>
                    @if ($mostrar)
                        @if (is_array($ruta['items']))
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">{{ $nombre }} <span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                @foreach ($ruta['items'] as $nombre2=>$ruta2)
                                    @if ($ruta2 == '_')
                                        <li class="divider"></li>
                                    @elseif (stripos($ruta2,"blade:") !== false)
                                        @include(str_replace("blade:","",$ruta2))
                                    @else
                                        <li><a href="{{ $ruta2 }}">{{ $nombre2 }}</a></li>
                                    @endif
                                @endforeach
                                </ul>
                            </li>
                        @elseif ($ruta['items'] == '_')
                            <li class="divider"></li>
                        @else
                            <li><a href="{{ $ruta['items'] }}">{{ $nombre }}</a></li>
                        @endif
                    @endif
                @elseif ($nombre == '_')
                    <li class="divider"></li>
                @else
                <li><a href="{{ $ruta }}">{{ $nombre }}</a></li>
                @endif
                @endforeach
                @yield("menuiobj")
            </ul>
            <ul class="nav navbar-nav navbar-right">
                @yield("menudobj")
                @foreach (Lang::get('menu.derecho') as $nombre=>$ruta)
                @if (is_array($ruta))
                    <?php
                    $mostrar = true;
                    if (isset($ruta['logedin'])) {
                        if ($ruta['logedin'] === true) {
                            $mostrar = Sentry::check();
                            if ($mostrar){
                                $nombre = str_replace("{first_name}",Sentry::getUser()->first_name,$nombre);
                                $nombre = str_replace("{name}",Sentry::getUser()->name,$nombre);
                                $nombre = str_replace("{picture}",Sentry::getUser()->picture,$nombre);
                            }
                        } elseif ($ruta['logedin'] === false) {
                            $mostrar = ! Sentry::check();
                        }
                    }
                    ?>
                    @if ($mostrar)
                        @if (is_array($ruta['items']))
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">{{ $nombre }} <span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                @foreach ($ruta['items'] as $nombre2=>$ruta2)
                                    @if ($ruta2 == '_')
                                        <li class="divider"></li>
                                    @elseif (stripos($ruta2,"blade:") !== false)
                                        @include(str_replace("blade:","",$ruta2))
                                    @else
                                        <?php
                                        if ($ruta['logedin'] === true) {
                                            $nombre2 = str_replace("{first_name}",Sentry::getUser()->first_name,$nombre2);
                                            $nombre2 = str_replace("{picture}",Sentry::getUser()->picture,$nombre2);
                                            $nombre2 = str_replace("{name}",Sentry::getUser()->name,$nombre2);
                                            $ruta2 = str_replace("{id}",Sentry::getUser()->id,$ruta2);
                                        }
                                        ?>
                                        <li><a href="{{ $ruta2 }}">{{ $nombre2 }}</a></li>
                                    @endif
                                @endforeach
                                </ul>
                            </li>
                        @elseif ($ruta['items'] == '_')
                            <li class="divider"></li>
                        @else
                            <li><a href="{{ $ruta['items'] }}">{{ $nombre }}</a></li>
                        @endif
                    @endif
                @elseif ($nombre == '_')
                <li class="divider"></li>
                @else
                <li><a href="{{ $ruta }}">{{ $nombre }}</a></li>
                @endif
                @endforeach
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>


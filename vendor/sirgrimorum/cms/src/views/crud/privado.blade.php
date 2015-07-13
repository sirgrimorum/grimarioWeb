<html>
    <head>
        <title>{{ Lang::get('cms::crud.privado.titulo') }}</title>
        {{ HTML::style("css/bootstrap.css") }}
    </head>
    <body>
        <div class="navbar navbar-default navbar-fixed-top" role="navigation">
            <div class="container">
                <div class="navbar-header">  

                    <!-- Left Control -->
                    <div class="sb-toggle-left navbar-left">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#menu_collapse">
                            <i class="fa fa-bars fa-3x"></i>
                        </button>
                    </div><!-- /.sb-control-left -->

                    <a class="navbar-brand" href="{{URL::action('Todosen4Controller@getIndex')}}">
                        <img src="{{ asset('images/img/TodosEn4_hap_logo.png') }}" class="img-responsive">
                    </a>

                </div>

                <!-- LINKS DE LA CABEZERA -->
                <div class="navbar-collapse collapse" id="menu_collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown">
                            <a class="dropdown-toggle" href="#" data-toggle="dropdown">{{ Lang::get('maintemplate.menu.ingresarreg') }} <strong class="caret"></strong>
                            </a>
                            <div class="dropdown-menu" id='intoptkg'>

                                <a href="{{URL::to('todosen4/loginfb')}}" class="btn btn-block btn-social btn-facebook">
                                    <i class="fa fa-facebook"></i> {{ Lang::get('cms::crud.privado.ingresarfb') }}
                                </a>

                            </div>
                        </li>
                    </ul>
                </div><!--/.nav-collapse -->
                <!-- END LINKS DE LA CABEZERA -->
   
            </div>
        </div>
        <div class="container">
            <h1>Usuario inválido</h1>

            @if (Session::has('message'))
            <div class="alert alert-info">{{ Session::pull('message') }}</div>
            @endif
        </div>
    </body>
</html>

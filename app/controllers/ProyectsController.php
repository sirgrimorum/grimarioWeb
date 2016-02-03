<?php

use Khill\Lavacharts\Lavacharts;

class ProyectsController extends \BaseController {

    /**
     * Display a listing of proyects
     *
     * @return Response
     */
    public function index() {
        $userSen = Sentry::getUser();
        $usuario = User::findOrFail($userSen->id);
        if (!$userSen->hasAccess("proyects")) {
            $messages = new Illuminate\Support\MessageBag;
            $messages->add('no_permission', Lang::get("user.mensaje.no_permission"));
            return Redirect::route("home")->withErrors($messages);
        }
        if ($userSen->inGroup(Sentry::findGroupByName('Lider'))) {
            $proyects = $usuario->proyects()->where("state", "<>", "ter")->get();
            $botonCrear = false;
            $configCampos = ['name', 'code', 'priority', 'state', 'advance', 'totalcost', 'totalplan'];
            $configBotones = [
                "<a class='btn btn-info' href='" . URL::route(Lang::get("principal.menu.links.proyecto") . '.show', array("{ID}")) . "'>" . Lang::get("proyect.labels.ver") . "</a>",
                "<a class='btn btn-success' href='" . URL::route(Lang::get("principal.menu.links.proyecto") . '.edit', array("{ID}")) . "'>" . Lang::get("proyect.labels.editar") . "</a>",
            ];
        }
        if ($userSen->inGroup(Sentry::findGroupByName('Coordinador'))) {
            $proyects = Proyect::where("state", "<>", "ter")->get();
            $botonCrear = true;
            $configCampos = ['name', 'code', 'priority', 'state', 'advance', 'totalcost', 'totalplan', 'user'];
            $configBotones = [
                "<a class='btn btn-info' href='" . URL::route(Lang::get("principal.menu.links.proyecto") . '.show', array("{ID}")) . "'>" . Lang::get("proyect.labels.ver") . "</a>",
                "<a class='btn btn-success' href='" . URL::route(Lang::get("principal.menu.links.proyecto") . '.edit', array("{ID}")) . "'>" . Lang::get("proyect.labels.editar") . "</a>",
            ];
        }
        if ($userSen->inGroup(Sentry::findGroupByName('Empresario')) || $userSen->inGroup(Sentry::findGroupByName('SuperAdmin'))) {
            $proyects = Proyect::all();
            $botonCrear = true;
            $configCampos = ['name', 'code', 'priority', 'state', 'advance', 'value', 'saves', 'profit', 'user'];
            $configBotones = [
                "<a class='btn btn-info' href='" . URL::route(Lang::get("principal.menu.links.proyecto") . '.show', array("{ID}")) . "'>" . Lang::get("proyect.labels.ver") . "</a>",
                "<a class='btn btn-success' href='" . URL::route(Lang::get("principal.menu.links.proyecto") . '.edit', array("{ID}")) . "'>" . Lang::get("proyect.labels.editar") . "</a>",
                "<a class='btn btn-danger' href='" . URL::route(Lang::get("principal.menu.links.proyecto") . '.destroy', array("{ID}")) . "'>" . Lang::get("proyect.labels.eliminar") . "</a>",
            ];
        }

        return View::make('modelos.proyects.index', ['proyects' => $proyects, 'botonCrear' => $botonCrear, 'configCampos' => $configCampos, 'configBotones' => $configBotones, 'userSen' => $userSen]);
    }

    /**
     * Show the form for creating a new proyect
     *
     * @return Response
     */
    public function create() {
        $userSen = Sentry::getUser();
        $usuario = User::findOrFail($userSen->id);
        if (!$userSen->hasAccess("proyects")) {
            $messages = new Illuminate\Support\MessageBag;
            $messages->add('no_permission', Lang::get("user.mensaje.no_permission"));
            return Redirect::route("home")->withErrors($messages);
        }
        if (Input::has('en')) {
            $enterpriseId = Input::get('en');
            $enterpise = Enterprise::find($enterpriseId);
            return View::make('modelos.proyects.create', ['enterprise' => $enterpise]);
        } else {
            return View::make('modelos.proyects.create');
        }
    }

    /**
     * Store a newly created proyect in storage.
     *
     * @return Response
     */
    public function store() {
        $validator = Validator::make($data = Input::except('pop', 'teams', 'enterprises'), Proyect::$rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }
        $file = Input::file('pop');

        if ($file) {
            if (substr($file->getMimeType(), 0, 5) == 'image') {
                $esImagen = true;
            } else {
                $esImagen = false;
            }
            $destinationPath = public_path() . '/images/proyects/';
            $filename = $file->getClientOriginalName();
            $filename = str_random(20) . "." . $file->getClientOriginalExtension();


            $upload_success = $file->move($destinationPath, $filename);

            if ($upload_success) {
                if ($esImagen) {
                    // resizing an uploaded file
                    Image::make($destinationPath . $filename)->resize(50, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save($destinationPath . "thumb/" . $filename, 100);
                }
                //return Response::json('success', 200);
            } else {
                return Response::json('error', 400);
            }
            $data = Input::except('_token', 'pop', 'teams', 'enterprises');
            $data['pop'] = $filename;
        } else {
            $data = Input::except('_token', 'pop', 'teams', 'enterprises');
        }

        $proyect = Proyect::create($data);

        if (Input::has('enterprises')) {
            if (is_array(Input::get('enterprises'))) {
                foreach (Input::get('enterprises') as $enterprise_id) {
                    $enterprise = Enterprise::find($enterprise_id);
                    $proyect->enterprises()->attach($enterprise);
                }
            } else {
                $enterprise = Enterprise::find(Input::get('enterprises'));
                $proyect->enterprises()->attach($enterprise);
            }
        }

        if (Input::has('teams')) {
            if (is_array(Input::get('teams'))) {
                foreach (Input::get('teams') as $team_id) {
                    $team = Team::find($team_id);
                    $proyect->teams()->attach($team);
                }
            } else {
                $team = Team::find(Input::get('teams'));
                $proyect->teams()->attach($team);
            }
        }
        Session::put('userTo', $proyect->user->id);
        Mail::send(array('emails.html.proyects.creado', 'emails.text.proyects.creado'), array('proyect' => $proyect, 'user' => Sentry::getUser(), 'userTo' => $proyect->user), function($message) {
            $user = User::find(Session::get('userTo'));
            $message->from(Lang::get("email.from_email"), Lang::get("email.from_name"));
            $message->to($user->email, $user->name)->subject(Lang::get("proyect.emails.titulos.creada"));
        });

        return Redirect::route(Lang::get("principal.menu.links.proyecto") . '.show', array($proyect->id));
    }

    /**
     * Display the specified proyect.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        $userSen = Sentry::getUser();
        $usuario = User::findOrFail($userSen->id);
        $botonVerInterno = true;
        $botonCrearEntregables = false;
        $botonCrearSupuestos = false;
        $botonCrearClientes = false;
        $configCampos = ['satisfaction', 'experience', 'totalcost', 'totalplan', 'value', 'saves', 'profit'];
        $configBotonesEntregables = "";
        $configBotonesSupuestos = "";
        $configBotonesClientes = "";
        $botonCrearIndicadores = false;
        $botonCrearRiesgos = false;
        $botonCrearActividades = false;
        $configBotonesIndicadores = "";
        $configBotonesRiesgos = "";
        $configBotonesActividades = "";
        if ($userSen->inGroup(Sentry::findGroupByName('Cliente'))) {
            $botonVerInterno = false;
            $configCampos = ['satisfaction', 'experience', 'totalcost', 'totalplan', 'value', 'saves', 'profit'];
        }
        if ($userSen->inGroup(Sentry::findGroupByName('Lider'))) {
            $botonCrearEntregables = false;
            $botonCrearSupuestos = true;
            $botonCrearClientes = true;
            $configCampos = ['satisfaction', 'experience', 'value', 'profit'];
            $configBotonesEntregables = "";
            $configBotonesSupuestos = [
                "<a class='btn btn-info' href='" . URL::route(Lang::get("principal.menu.links.restriccion") . '.show', array("{ID}")) . "'>" . Lang::get("restriction.labels.ver") . "</a>",
                "<a class='btn btn-success' href='" . URL::route(Lang::get("principal.menu.links.restriccion") . '.edit', array("{ID}")) . "'>" . Lang::get("restriction.labels.editar") . "</a>",
            ];
            $configBotonesClientes = [
                "<a class='btn btn-info' href='" . URL::route(Lang::get("principal.menu.links.userdata") . '.show', array("{ID}")) . "'>" . Lang::get("userdata.labels.ver") . "</a>",
                "<a class='btn btn-success' href='" . URL::route(Lang::get("principal.menu.links.userdata") . '.edit', array("{ID}")) . "'>" . Lang::get("userdata.labels.editar") . "</a>",
            ];
            $botonCrearIndicadores = true;
            $botonCrearRiesgos = true;
            $botonCrearActividades = true;
            $configBotonesIndicadores = [
                "<a class='btn btn-info' href='" . URL::route(Lang::get("principal.menu.links.indicador") . '.show', array("{ID}")) . "'>" . Lang::get("indicator.labels.ver") . "</a>",
                "<a class='btn btn-success' href='" . URL::route(Lang::get("principal.menu.links.indicador") . '.edit', array("{ID}")) . "'>" . Lang::get("indicator.labels.editar") . "</a>",
                "<a class='btn btn-danger' href='" . URL::route(Lang::get("principal.menu.links.indicador") . '.destroy', array("{ID}")) . "'>" . Lang::get("indicator.labels.eliminar") . "</a>",
            ];
            $configBotonesRiesgos = [
                "<a class='btn btn-info' href='" . URL::route(Lang::get("principal.menu.links.riesgo") . '.show', array("{ID}")) . "'>" . Lang::get("risk.labels.ver") . "</a>",
                "<a class='btn btn-success' href='" . URL::route(Lang::get("principal.menu.links.riesgo") . '.edit', array("{ID}")) . "'>" . Lang::get("risk.labels.editar") . "</a>",
                "<a class='btn btn-danger' href='" . URL::route(Lang::get("principal.menu.links.riesgo") . '.destroy', array("{ID}")) . "'>" . Lang::get("risk.labels.eliminar") . "</a>",
            ];
            $configBotonesActividades = [
                "<a class='btn btn-info' href='" . URL::route(Lang::get("principal.menu.links.tarea") . '.show', array("{ID}")) . "'>" . Lang::get("task.labels.ver") . "</a>",
                "<a class='btn btn-success' href='" . URL::route(Lang::get("principal.menu.links.tarea") . '.edit', array("{ID}")) . "'>" . Lang::get("task.labels.editar") . "</a>",
                "<a class='btn btn-danger' href='" . URL::route(Lang::get("principal.menu.links.tarea") . '.destroy', array("{ID}")) . "'>" . Lang::get("task.labels.eliminar") . "</a>",
            ];
        }
        if ($userSen->inGroup(Sentry::findGroupByName('Coordinador'))) {
            $botonCrearEntregables = true;
            $botonCrearSupuestos = true;
            $botonCrearClientes = true;
            $configCampos = [ 'value', 'profit'];
            $configBotonesEntregables = [
                "<a class='btn btn-info' href='" . URL::route(Lang::get("principal.menu.links.pago") . '.show', array("{ID}")) . "'>" . Lang::get("payment.labels.ver") . "</a>",
                "<a class='btn btn-success' href='" . URL::route(Lang::get("principal.menu.links.pago") . '.edit', array("{ID}")) . "'>" . Lang::get("payment.labels.editar") . "</a>",
                "<a class='btn btn-danger' href='" . URL::route(Lang::get("principal.menu.links.pago") . '.destroy', array("{ID}")) . "'>" . Lang::get("payment.labels.eliminar") . "</a>",
            ];
            $configBotonesSupuestos = [
                "<a class='btn btn-info' href='" . URL::route(Lang::get("principal.menu.links.restriccion") . '.show', array("{ID}")) . "'>" . Lang::get("restriction.labels.ver") . "</a>",
                "<a class='btn btn-success' href='" . URL::route(Lang::get("principal.menu.links.restriccion") . '.edit', array("{ID}")) . "'>" . Lang::get("restriction.labels.editar") . "</a>",
            ];
            $configBotonesClientes = [
                "<a class='btn btn-info' href='" . URL::route(Lang::get("principal.menu.links.userdata") . '.show', array("{ID}")) . "'>" . Lang::get("userdata.labels.ver") . "</a>",
                "<a class='btn btn-success' href='" . URL::route(Lang::get("principal.menu.links.userdata") . '.edit', array("{ID}")) . "'>" . Lang::get("userdata.labels.editar") . "</a>",
            ];
            $botonCrearIndicadores = true;
            $botonCrearRiesgos = true;
            $botonCrearActividades = true;
            $configBotonesIndicadores = [
                "<a class='btn btn-info' href='" . URL::route(Lang::get("principal.menu.links.indicador") . '.show', array("{ID}")) . "'>" . Lang::get("indicator.labels.ver") . "</a>",
                "<a class='btn btn-success' href='" . URL::route(Lang::get("principal.menu.links.indicador") . '.edit', array("{ID}")) . "'>" . Lang::get("indicator.labels.editar") . "</a>",
                "<a class='btn btn-danger' href='" . URL::route(Lang::get("principal.menu.links.indicador") . '.destroy', array("{ID}")) . "'>" . Lang::get("indicator.labels.eliminar") . "</a>",
            ];
            $configBotonesRiesgos = [
                "<a class='btn btn-info' href='" . URL::route(Lang::get("principal.menu.links.riesgo") . '.show', array("{ID}")) . "'>" . Lang::get("risk.labels.ver") . "</a>",
                "<a class='btn btn-success' href='" . URL::route(Lang::get("principal.menu.links.riesgo") . '.edit', array("{ID}")) . "'>" . Lang::get("risk.labels.editar") . "</a>",
                "<a class='btn btn-danger' href='" . URL::route(Lang::get("principal.menu.links.riesgo") . '.destroy', array("{ID}")) . "'>" . Lang::get("risk.labels.eliminar") . "</a>",
            ];
            $configBotonesActividades = [
                "<a class='btn btn-info' href='" . URL::route(Lang::get("principal.menu.links.tarea") . '.show', array("{ID}")) . "'>" . Lang::get("task.labels.ver") . "</a>",
                "<a class='btn btn-success' href='" . URL::route(Lang::get("principal.menu.links.tarea") . '.edit', array("{ID}")) . "'>" . Lang::get("task.labels.editar") . "</a>",
                "<a class='btn btn-danger' href='" . URL::route(Lang::get("principal.menu.links.tarea") . '.destroy', array("{ID}")) . "'>" . Lang::get("task.labels.eliminar") . "</a>",
            ];
        }
        if ($userSen->inGroup(Sentry::findGroupByName('Empresario')) || $userSen->inGroup(Sentry::findGroupByName('SuperAdmin'))) {
            $botonCrearEntregables = true;
            $botonCrearSupuestos = true;
            $configCampos = [];
            $configBotonesEntregables = [
                "<a class='btn btn-info' href='" . URL::route(Lang::get("principal.menu.links.pago") . '.show', array("{ID}")) . "'>" . Lang::get("payment.labels.ver") . "</a>",
                "<a class='btn btn-success' href='" . URL::route(Lang::get("principal.menu.links.pago") . '.edit', array("{ID}")) . "'>" . Lang::get("payment.labels.editar") . "</a>",
                "<a class='btn btn-danger' href='" . URL::route(Lang::get("principal.menu.links.pago") . '.destroy', array("{ID}")) . "'>" . Lang::get("payment.labels.eliminar") . "</a>",
            ];
            $configBotonesSupuestos = [
                "<a class='btn btn-info' href='" . URL::route(Lang::get("principal.menu.links.restriccion") . '.show', array("{ID}")) . "'>" . Lang::get("restriction.labels.ver") . "</a>",
                "<a class='btn btn-success' href='" . URL::route(Lang::get("principal.menu.links.restriccion") . '.edit', array("{ID}")) . "'>" . Lang::get("restriction.labels.editar") . "</a>",
                "<a class='btn btn-danger' href='" . URL::route(Lang::get("principal.menu.links.restriccion") . '.destroy', array("{ID}")) . "'>" . Lang::get("restriction.labels.eliminar") . "</a>",
            ];
            $configBotonesClientes = [
                "<a class='btn btn-info' href='" . URL::route(Lang::get("principal.menu.links.userdata") . '.show', array("{ID}")) . "'>" . Lang::get("userdata.labels.ver") . "</a>",
                "<a class='btn btn-success' href='" . URL::route(Lang::get("principal.menu.links.userdata") . '.edit', array("{ID}")) . "'>" . Lang::get("userdata.labels.editar") . "</a>",
            ];
            $botonCrearIndicadores = true;
            $botonCrearRiesgos = true;
            $botonCrearActividades = true;
            $configBotonesIndicadores = [
                "<a class='btn btn-info' href='" . URL::route(Lang::get("principal.menu.links.indicador") . '.show', array("{ID}")) . "'>" . Lang::get("indicator.labels.ver") . "</a>",
                "<a class='btn btn-success' href='" . URL::route(Lang::get("principal.menu.links.indicador") . '.edit', array("{ID}")) . "'>" . Lang::get("indicator.labels.editar") . "</a>",
                "<a class='btn btn-danger' href='" . URL::route(Lang::get("principal.menu.links.indicador") . '.destroy', array("{ID}")) . "'>" . Lang::get("indicator.labels.eliminar") . "</a>",
            ];
            $configBotonesRiesgos = [
                "<a class='btn btn-info' href='" . URL::route(Lang::get("principal.menu.links.riesgo") . '.show', array("{ID}")) . "'>" . Lang::get("risk.labels.ver") . "</a>",
                "<a class='btn btn-success' href='" . URL::route(Lang::get("principal.menu.links.riesgo") . '.edit', array("{ID}")) . "'>" . Lang::get("risk.labels.editar") . "</a>",
                "<a class='btn btn-danger' href='" . URL::route(Lang::get("principal.menu.links.riesgo") . '.destroy', array("{ID}")) . "'>" . Lang::get("risk.labels.eliminar") . "</a>",
            ];
            $configBotonesActividades = [
                "<a class='btn btn-info' href='" . URL::route(Lang::get("principal.menu.links.tarea") . '.show', array("{ID}")) . "'>" . Lang::get("task.labels.ver") . "</a>",
                "<a class='btn btn-success' href='" . URL::route(Lang::get("principal.menu.links.tarea") . '.edit', array("{ID}")) . "'>" . Lang::get("task.labels.editar") . "</a>",
                "<a class='btn btn-danger' href='" . URL::route(Lang::get("principal.menu.links.tarea") . '.destroy', array("{ID}")) . "'>" . Lang::get("task.labels.eliminar") . "</a>",
            ];
        }
        $proyect = Proyect::findOrFail($id);

        if ($botonVerInterno) {
            /* $dtEntregasPer = Lava::DataTable();
              $dtEntregasPer->addStringColumn(Lang::get("payment.labels.value") . " " . Lang::get("payment.labels.pagos"))
              ->addNumberColumn(Lang::get("payment.labels.value"));
              foreach ($proyect->payments()->get() as $payment) {
              $dtEntregasPer->addRow(array($payment->name, $payment->value));
              }
              $pieEntregasPer = Lava::PieChart("payments_per")
              ->setOptions(array(
              'datatable' => $dtEntregasPer,
              'title' => Lang::get("payment.labels.pagos"),
              'is3D' => true,
              )); */
            /*$dtEntregasPer = Lava::DataTable();
            $dtEntregasPer->addStringColumn(Lang::get("payment.labels.value") . " " . Lang::get("payment.labels.pagos"))
                    ->addNumberColumn(Lang::get("payment.labels.value"))
                    ->addNumberColumn(Lang::get("payment.labels.plan"))
                    ->addNumberColumn(Lang::get("payment.labels.totalcost"))
                    ->addNumberColumn(Lang::get("payment.labels.profit"));
            foreach ($proyect->payments()->orderBy("plandate", "ASC")->get() as $payment) {
                $dtEntregasPer->addRow(array($payment->name, $payment->value, $payment->plan, $payment->totalcost(), $payment->profit()));
            }
            $pieEntregasPer = Lava::ComboChart("payments_per", $dtEntregasPer, [
                        'title' => Lang::get("payment.labels.pagos"),
                        'legend' => [
                            'position' => 'in'
                        ],
                        'vAxis' => [
                            'format' => 'currency',
                        ],
                        'seriesType' => 'bars',
                        'height' => 350,
                        'series' => array(
                            3 => [
                                'type' => 'line'
                            ]
                        )
            ]);*/
            $dtEntregasAv = Lava::DataTable();
            $dtEntregasAv->addStringColumn(Lang::get("payment.labels.pagos"));
            $arrPercentage = array(Lang::get("payment.labels.percentage"));
            $arrAdvance = array(Lang::get("payment.labels.advance"));
            foreach ($proyect->payments()->orderBy("plandate", "ASC")->get() as $payment) {
                $dtEntregasAv->addNumberColumn($payment->name);
                array_push($arrPercentage, $payment->percentage);
                array_push($arrAdvance, $payment->contribution());
            }
            $dtEntregasAv->addRow($arrPercentage);
            $dtEntregasAv->addRow($arrAdvance);
            $barEntregasAv = Lava::ColumnChart("payments_av", $dtEntregasAv, [
                        'title' => Lang::get("payment.labels.advance"),
                        'legend' => [
                            'position' => 'in'
                        ],
                        'vAxis' => [
                            'format' => 'percent',
                        ],
                        'isStacked' => true,
                        'height' => 350,
                            //'is3D' => true,
            ]);

            /*
            $dtPresup = Lava::DataTable();
            $dtPresup->addStringColumn(Lang::get("proyect.labels.presupuesto"))
                    ->addNumberColumn(Lang::get("proyect.labels.totalplan"))
                    ->addNumberColumn(Lang::get("proyect.labels.totalcost"))
                    ->addRow(array("COP", $proyect->totalplan(), $proyect->totalcost()));
            $barPresup = Lava::BarChart("proyect_presup", $dtPresup, [
                        //'theme' => 'maximized',
                        'hAxis' => [
                            'format' => 'currency',
                        ],
                        //'title' => Lang::get("proyect.labels.presupuesto"),
                        'legend' => [
                            'position' => 'none',
                        ],
                        'theme' => 'maximized',
                            //'orientation' => 'horizontal',
            ]);
             *
             */
        }
        return View::make('modelos.proyects.show', [
                    "proyect" => $proyect,
                    "userSen" => $userSen,
                    "botonVerInterno" => $botonVerInterno,
                    "botonCrearEntregables" => $botonCrearEntregables,
                    "botonCrearSupuestos" => $botonCrearSupuestos,
                    "botonCrearClientes" => $botonCrearClientes,
                    "configCampos" => $configCampos,
                    "configBotonesEntregables" => $configBotonesEntregables,
                    "configBotonesSupuestos" => $configBotonesSupuestos,
                    "configBotonesClientes" => $configBotonesClientes,
                    "configBotonesIndicadores" => $configBotonesIndicadores,
                    "configBotonesRiesgos" => $configBotonesRiesgos,
                    "configBotonesActividades" => $configBotonesActividades,
                    "botonCrearIndicadores" => $botonCrearIndicadores,
                    "botonCrearRiesgos" => $botonCrearRiesgos,
                    "botonCrearActividades" => $botonCrearActividades,
        ]);
    }

    /**
     * Show the form for editing the specified proyect.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        $userSen = Sentry::getUser();
        $usuario = User::findOrFail($userSen->id);
        if (!$userSen->hasAccess("proyects")) {
            $messages = new Illuminate\Support\MessageBag;
            $messages->add('no_permission', Lang::get("user.mensaje.no_permission"));
            return Redirect::route("home")->withErrors($messages);
        }
        $proyect = Proyect::find($id);
        if (Input::has('en')) {
            $enterpriseId = Input::get('en');
            $enterpise = Enterprise::find($enterpriseId);
            return View::make('modelos.proyects.edit', ['proyect' => $proyect, 'enterprise' => $enterpise]);
        } elseif (Input::has('pre')) {
            if (!($userSen->inGroup(Sentry::findGroupByName('Empresario')) or $userSen->inGroup(Sentry::findGroupByName('SuperAdmin')))) {
                $messages = new Illuminate\Support\MessageBag;
                $messages->add('no_permission', Lang::get("user.mensaje.no_permission"));
                return Redirect::route("home")->withErrors($messages);
            }
            return View::make('modelos.proyects.updatepresup', ['proyect' => $proyect]);
        }else {
            return View::make('modelos.proyects.edit', ['proyect' => $proyect]);
        }
    }

    /**
     * Update the specified proyect in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        $proyect = Proyect::findOrFail($id);
        if (Input::has('act_presup')) {
            foreach ($proyect->payments()->orderBy('paymentdate','DESC')->get() as $payment) {
                if (Input::has("proyect_payments_p_" . $payment->id) && Input::has("proyect_payments_h_" . $payment->id)) {
                    //$user_task = $task->users()->find($worker->id);
                    //$user_task->pivot->calification = Input::get("work_users_c_" . $worker->id);
                    //$user_task->pivot->save();
                    $payment->plan=Input::get("proyect_payments_p_" . $payment->id);
                    $payment->planh=Input::get("proyect_payments_h_" . $payment->id);
                    $payment->save();
                }
            }
            return Redirect::route(Lang::get("principal.menu.links.proyecto") . '.show', array($proyect->id))->with('message', Lang::get("task.mensajes.actualizadopresup"));
            //return Redirect::route(Lang::get("principal.menu.links.tarea") . '.show', array($task->id));
        } else {
            $validator = Validator::make($data = Input::except('pop_nue'), array_except(Proyect::$rules, [ 'name', 'code']));

            if ($validator->fails()) {
                return Redirect::back()->withErrors($validator)->withInput();
            }

            $file = Input::file('pop_nue');

            if ($file) {
                if (substr($file->getMimeType(), 0, 5) == 'image') {
                    $esImagen = true;
                } else {
                    $esImagen = false;
                }
                $destinationPath = public_path() . '/images/proyects/';
                $filename = $file->getClientOriginalName();
                $filename = str_random(20) . "." . $file->getClientOriginalExtension();


                $upload_success = $file->move($destinationPath, $filename);

                if ($upload_success) {

                    if ($esImagen) {
                        // resizing an uploaded file
                        Image::make($destinationPath . $filename)->resize(50, null, function ($constraint) {
                            $constraint->aspectRatio();
                        })->save($destinationPath . "thumb/" . $filename, 100);
                    }
                    //return Response::json('success', 200);
                } else {
                    return Response::json('error', 400);
                }
                $data = Input::except('_token', 'pop', 'pop_nue', 'teams', 'enterprises');
                $data['pop'] = $filename;
            } else {
                $data = Input::except('_token', 'pop_nue', 'teams', 'enterprises');
            }

            if (Input::has('teams')) {
                if (is_array(Input::get('teams'))) {
                    $ides = array();
                    foreach (Input::get('teams') as $team_id) {
                        array_push($ides, $team_id);
                        //$team = Team::find($team_id);
                        //$proyect->teams()->attach($team);
                    }
                    $proyect->teams()->sync($ides);
                } else {
                    $team = Team::find(Input::get('teams'));
                    $proyect->teams()->sync($team);
                }
            }


            $proyect->update($data);

            return Redirect::route(Lang::get("principal.menu.links.proyecto") . '.index');
        }
    }

    /**
     * Remove the specified proyect from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        Proyect::destroy($id);

        return Redirect::route('modelos.proyects.index');
    }

}

<?php

use Khill\Lavacharts\Lavacharts;

class PaymentsController extends BaseController {

    /**
     * Display a listing of payments
     *
     * @return Response
     */
    public function index() {

        $payments = Payment::all();

        return View::make('modelos.payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new payment
     *
     * @return Response
     */
    public function create() {
        $userSen = Sentry::getUser();
        if (!$userSen->inGroup(Sentry::findGroupByName('Lider'))) {
            $messages = new Illuminate\Support\MessageBag;
            $messages->add('no_permission', Lang::get("user.mensaje.no_permission"));
            return Redirect::route("home")->withErrors($messages);
        }
        if (Input::has('pr')) {
            $proyectId = Input::get('pr');
            $proyect = Proyect::find($proyectId);
            return View::make('modelos.payments.create', ['proyect' => $proyect]);
        } else {
            return View::make('modelos.payments.create');
        }
    }

    /**
     * Store a newly created payment in storage.
     *
     * @return Response
     */
    public function store() {
        $data = Input::all();
        if (!Input::has("plan")) {
            $data['plan'] = 0;
        }
        $validator = Validator::make($data, Payment::$rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }
        $data = Input::except('_token');
        if (!Input::has("plan")) {
            $data['plan'] = 0;
        }

        $payment = Payment::create($data);

        return Redirect::route(Lang::get("principal.menu.links.proyecto") . '.show', array($payment->proyect->id, 'py' => $payment->id))->with('message', Lang::get("payment.mensajes.creado"));
    }

    /**
     * Display the specified payment.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        $userSen = Sentry::getUser();
        if ($userSen->inGroup(Sentry::findGroupByName('Jugador'))) {
            $botonCrearIndicadores = false;
            $botonCrearRiesgos = false;
            $botonCrearActividades = false;
            $configBotonesIndicadores = "";
            $configBotonesRiesgos = "";
            $configBotonesActividades = "";
        }
        if ($userSen->inGroup(Sentry::findGroupByName('Lider')) || $userSen->inGroup(Sentry::findGroupByName('Coordinador'))) {
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
        $payment = Payment::findOrFail($id);

        $dtTareasPer = Lava::DataTable();
        $dtTareasPer->addStringColumn(Lang::get("task.labels.tareas"))
                ->addNumberColumn('percentage');
        foreach ($payment->tasks()->get() as $task) {
            $dtTareasPer->addRow(array($task->name, $task->percentage / 100));
        }
        $pieTareasPer = Lava::PieChart("tasks_per")->setOptions(array(
            'datatable' => $dtTareasPer,
            'title' => Lang::get("task.labels.percentage"),
            'is3D' => true,
        ));

        return View::make('modelos.payments.show', [
                    "payment" => $payment,
                    "userSen" => $userSen,
                    "configBotonesIndicadores" => $configBotonesIndicadores,
                    "configBotonesRiesgos" => $configBotonesRiesgos,
                    "configBotonesActividades" => $configBotonesActividades,
                    "botonCrearIndicadores" => $botonCrearIndicadores,
                    "botonCrearRiesgos" => $botonCrearRiesgos,
                    "botonCrearActividades" => $botonCrearActividades,
        ]);
    }

    /**
     * Show the form for editing the specified payment.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        $userSen = Sentry::getUser();
        $payment = Payment::find($id);
        if (Input::has('st')) {
            if (!($userSen->inGroup(Sentry::findGroupByName('Lider')) or $userSen->inGroup(Sentry::findGroupByName('Coordinador')) or $userSen->inGroup(Sentry::findGroupByName('Empresario')) or $userSen->inGroup(Sentry::findGroupByName('SuperAdmin')))) {
                $messages = new Illuminate\Support\MessageBag;
                $messages->add('no_permission', Lang::get("user.mensaje.no_permission"));
                return Redirect::route("home")->withErrors($messages);
            }
            $data = $payment->getAttributes();
            $data['state'] = Input::get('st');
            $data['paymentdate'] = 0;
            if (Input::get('st') == 'pag') {
                $data['paymentdate'] = date("Y-m-d H:i:s");
            }
            $validator = Validator::make($data, array_except(Payment::$rules, 'paymentdate'));
            if ($validator->fails()) {
                return Redirect::back()->withErrors($validator)->withInput();
            }
            $payment->update($data);
            if (Input::get('st') == 'ent') {
                $mensaje = Lang::get("payment.mensajes.entregado");
            } elseif (Input::get('st') == 'pag') {
                $mensaje = Lang::get("payment.mensajes.pagado");
            }
            return Redirect::route(Lang::get("principal.menu.links.pago") . '.show', array($payment->id))->with('message', $mensaje);
        } elseif (Input::has('pre')) {
            if (!($userSen->inGroup(Sentry::findGroupByName('Empresario')) or $userSen->inGroup(Sentry::findGroupByName('SuperAdmin')))) {
                $messages = new Illuminate\Support\MessageBag;
                $messages->add('no_permission', Lang::get("user.mensaje.no_permission"));
                return Redirect::route("home")->withErrors($messages);
            }
            return View::make('modelos.payments.updatepresup', compact('payment'));
        } else {
            if (!($userSen->inGroup(Sentry::findGroupByName('Coordinador')) or $userSen->inGroup(Sentry::findGroupByName('Empresario')) or $userSen->inGroup(Sentry::findGroupByName('SuperAdmin')))) {
                $messages = new Illuminate\Support\MessageBag;
                $messages->add('no_permission', Lang::get("user.mensaje.no_permission"));
                return Redirect::route("home")->withErrors($messages);
            }
            return View::make('modelos.payments.edit', compact('payment'));
        }
    }

    /**
     * Update the specified payment in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        $payment = Payment::findOrFail($id);

        if (Input::has('pre')) {
            $data = $payment->getAttributes();
            $payment->plan = Input::get("plan");
            $payment->planh = Input::get("planh");
            $payment->save();
            return Redirect::route(Lang::get("principal.menu.links.proyecto") . '.show', array($payment->proyect->id, 'py' => $payment->id))->with('message', Lang::get("payment.mensajes.actualizado"));
        } else {
            $validator = Validator::make($data = Input::all(), Payment::$rules);

            if ($validator->fails()) {
                return Redirect::back()->withErrors($validator)->withInput();
            }

            $data = Input::except('_token');

            $payment->update($data);
        }
        return Redirect::route(Lang::get("principal.menu.links.proyecto") . '.show', array($payment->proyect->id, 'py' => $payment->id))->with('message', Lang::get("payment.mensajes.actualizado"));
        //return Redirect::route(Lang::get("principal.menu.links.pago") . '.show', array($payment->id));
    }

    /**
     * Remove the specified payment from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        Payment::destroy($id);

        return Redirect::route('payments.index');
    }

}

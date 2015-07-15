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
        if (!$userSen->inGroup(Sentry::findGroupByName('Director'))) {
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
        $validator = Validator::make(Input::all(), Payment::$rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }
        $data = Input::except('_token');

        $payment = Payment::create($data);

        return Redirect::route(Lang::get("principal.menu.links.pago") . '.show', array($payment->id));
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
        if ($userSen->inGroup(Sentry::findGroupByName('Coordinador')) || $userSen->inGroup(Sentry::findGroupByName('Director'))) {
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
            $dtTareasPer->addRow(array($task->name, $task->percentage));
        }
        $pieTareasPer = Lava::PieChart("tasks_per")
                ->setOptions(array(
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
        if (!$userSen->inGroup(Sentry::findGroupByName('Director'))) {
            $messages = new Illuminate\Support\MessageBag;
            $messages->add('no_permission', Lang::get("user.mensaje.no_permission"));
            return Redirect::route("home")->withErrors($messages);
        }
        $payment = Payment::find($id);

        return View::make('modelos.payments.edit', compact('payment'));
    }

    /**
     * Update the specified payment in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        $payment = Payment::findOrFail($id);

        $validator = Validator::make($data = Input::all(), Payment::$rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $data = Input::except('_token');

        $payment->update($data);

        return Redirect::route(Lang::get("principal.menu.links.pago") . '.show', array($payment->id));
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

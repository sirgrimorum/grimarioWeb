<?php

class IndicatorsController extends BaseController {

    /**
     * Display a listing of indicators
     *
     * @return Response
     */
    public function index() {
        $indicators = Indicator::all();

        return View::make('modelos.indicators.index', compact('indicators'));
    }

    /**
     * Show the form for creating a new indicator
     *
     * @return Response
     */
    public function create() {
        $userSen = Sentry::getUser();
        if (!$userSen->hasAccess("proyects")) {
            $messages = new Illuminate\Support\MessageBag;
            $messages->add('no_permission', Lang::get("user.mensaje.no_permission"));
            return Redirect::route("home")->withErrors($messages);
        }
        if (Input::has('py')) {
            $paymentId = Input::get('py');
            $payment = Payment::find($paymentId);
            $proyect = $payment->proyect;
            $user = Sentry::getUser();

            return View::make('modelos.indicators.create', ['proyect' => $proyect, 'payment' => $payment, 'user' => $user]);
        } else {
            return View::make('modelos.indicators.create');
        }
    }

    /**
     * Store a newly created indicator in storage.
     *
     * @return Response
     */
    public function store() {
        $validator = Validator::make($data = Input::all(), Indicator::$rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $data = Input::except('_token');

        $indicator = Indicator::create($data);

        return Redirect::route(Lang::get("principal.menu.links.pago") . '.show', array($indicator->payment->id));
    }

    /**
     * Display the specified indicator.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        
        $indicator = Indicator::findOrFail($id);

        $payment = $indicator->payment;
        $proyect = $payment->proyect;
        $user = Sentry::getUser();

        return View::make('modelos.indicators.show', ['indicator'=>$indicator, 'proyect' => $proyect, 'payment' => $payment, 'user' => $user]);
    }

    /**
     * Show the form for editing the specified indicator.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        $userSen = Sentry::getUser();
        if (!$userSen->hasAccess("proyects")) {
            $messages = new Illuminate\Support\MessageBag;
            $messages->add('no_permission', Lang::get("user.mensaje.no_permission"));
            return Redirect::route("home")->withErrors($messages);
        }
        $indicator = Indicator::find($id);

        return View::make('modelos.indicators.edit', compact('indicator'));
    }

    /**
     * Update the specified indicator in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        $indicator = Indicator::findOrFail($id);

        $validator = Validator::make($data = Input::all(), Indicator::$rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }
        $data = Input::except('_token', 'tk', 'st');

        $indicator->update($data);

        if (Input::has('tk') && Input::has('st')) {
            return Redirect::to(URL::route(Lang::get("principal.menu.links.tarea") . '.edit', array(Input::get('tk'))) . "?st=" . Input::get('st'));
        } else {
            return Redirect::route(Lang::get("principal.menu.links.pago") . '.show', array($indicator->payment->id));
        }
    }

    /**
     * Remove the specified indicator from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        $indicator = Indicator::findOrFail($id);
        $payment_id = $indicator->payment->id;

        Indicator::destroy($id);

        return Redirect::route(Lang::get("principal.menu.links.pago") . '.show', array($payment_id));
    }

}

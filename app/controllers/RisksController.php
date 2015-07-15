<?php

class RisksController extends BaseController {

    /**
     * Display a listing of risks
     *
     * @return Response
     */
    public function index() {
        $risks = Risk::all();

        return View::make('modelos.risks.index', compact('risks'));
    }

    /**
     * Show the form for creating a new risk
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

            return View::make('modelos.risks.create', ['proyect' => $proyect, 'payment' => $payment, 'user' => $user]);
        } else {
            return View::make('modelos.risks.create');
        }
    }

    /**
     * Store a newly created risk in storage.
     *
     * @return Response
     */
    public function store() {
        $validator = Validator::make($data = Input::all(), Risk::$rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $data = Input::except('_token');

        $risk = Risk::create($data);

        return Redirect::route(Lang::get("principal.menu.links.pago"). '.show', array($risk->payment->id));
    }

    /**
     * Display the specified risk.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        $risk = Risk::findOrFail($id);

        return View::make('modelos.risks.show', compact('risk'));
    }

    /**
     * Show the form for editing the specified risk.
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
        $risk = Risk::find($id);

        return View::make('modelos.risks.edit', compact('risk'));
    }

    /**
     * Update the specified risk in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        $risk = Risk::findOrFail($id);

        $validator = Validator::make($data = Input::all(), Risk::$rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $data = Input::except('_token');

        $risk->update($data);

        return Redirect::route(Lang::get("principal.menu.links.pago"). '.show', array($risk->payment->id));
    }

    /**
     * Remove the specified risk from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        $risk = Risk::findOrFail($id);
        $payment_id = $risk->payment->id;
        
        Risk::destroy($id);

        return Redirect::route(Lang::get("principal.menu.links.pago"). '.show', array($payment_id));
    }

}

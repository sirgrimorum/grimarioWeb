<?php

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

        return Redirect::route(Lang::get("principal.menu.links.pago"). '.show', array($payment->id));
    }

    /**
     * Display the specified payment.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        $payment = Payment::findOrFail($id);

        return View::make('modelos.payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified payment.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
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

        return Redirect::route(Lang::get("principal.menu.links.pago"). '.show', array($payment->id));
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

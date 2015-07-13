<?php

class MachinesController extends BaseController {

	/**
	 * Display a listing of machines
	 *
	 * @return Response
	 */
	public function index()
	{
		$machines = Machine::all();

		return View::make('modelos.machines.index', compact('machines'));
	}

	/**
	 * Show the form for creating a new machine
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('modelos.machines.create');
	}

	/**
	 * Store a newly created machine in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = Input::all(), Machine::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		Machine::create($data);

		return Redirect::route('machines.index');
	}

	/**
	 * Display the specified machine.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$machine = Machine::findOrFail($id);

		return View::make('modelos.machines.show', compact('machine'));
	}

	/**
	 * Show the form for editing the specified machine.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$machine = Machine::find($id);

		return View::make('modelos.machines.edit', compact('machine'));
	}

	/**
	 * Update the specified machine in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$machine = Machine::findOrFail($id);

		$validator = Validator::make($data = Input::all(), Machine::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$machine->update($data);

		return Redirect::route('machines.index');
	}

	/**
	 * Remove the specified machine from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Machine::destroy($id);

		return Redirect::route('machines.index');
	}

}

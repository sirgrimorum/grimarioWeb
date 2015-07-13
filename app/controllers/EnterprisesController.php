<?php

class EnterprisesController extends \BaseController {

	/**
	 * Display a listing of enterprises
	 *
	 * @return Response
	 */
	public function index()
	{
		$enterprises = Enterprise::all();

		return View::make('enterprises.index', compact('enterprises'));
	}

	/**
	 * Show the form for creating a new enterprise
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('enterprises.create');
	}

	/**
	 * Store a newly created enterprise in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = Input::all(), Enterprise::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		Enterprise::create($data);

		return Redirect::route('enterprises.index');
	}

	/**
	 * Display the specified enterprise.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$enterprise = Enterprise::findOrFail($id);

		return View::make('enterprises.show', compact('enterprise'));
	}

	/**
	 * Show the form for editing the specified enterprise.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$enterprise = Enterprise::find($id);

		return View::make('enterprises.edit', compact('enterprise'));
	}

	/**
	 * Update the specified enterprise in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$enterprise = Enterprise::findOrFail($id);

		$validator = Validator::make($data = Input::all(), Enterprise::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$enterprise->update($data);

		return Redirect::route('enterprises.index');
	}

	/**
	 * Remove the specified enterprise from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Enterprise::destroy($id);

		return Redirect::route('enterprises.index');
	}

}

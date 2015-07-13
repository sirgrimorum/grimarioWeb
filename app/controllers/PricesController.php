<?php

class PricesController extends \BaseController {

	/**
	 * Display a listing of prices
	 *
	 * @return Response
	 */
	public function index()
	{
		$prices = Price::all();

		return View::make('prices.index', compact('prices'));
	}

	/**
	 * Show the form for creating a new price
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('prices.create');
	}

	/**
	 * Store a newly created price in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = Input::all(), Price::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		Price::create($data);

		return Redirect::route('prices.index');
	}

	/**
	 * Display the specified price.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$price = Price::findOrFail($id);

		return View::make('prices.show', compact('price'));
	}

	/**
	 * Show the form for editing the specified price.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$price = Price::find($id);

		return View::make('prices.edit', compact('price'));
	}

	/**
	 * Update the specified price in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$price = Price::findOrFail($id);

		$validator = Validator::make($data = Input::all(), Price::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$price->update($data);

		return Redirect::route('prices.index');
	}

	/**
	 * Remove the specified price from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Price::destroy($id);

		return Redirect::route('prices.index');
	}

}

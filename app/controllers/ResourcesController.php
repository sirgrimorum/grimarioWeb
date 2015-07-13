<?php

class ResourcesController extends BaseController {

	/**
	 * Display a listing of resources
	 *
	 * @return Response
	 */
	public function index()
	{
		$resources = Resource::all();

		return View::make('modelos.resources.index', compact('resources'));
	}

	/**
	 * Show the form for creating a new resource
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('modelos.resources.create');
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = Input::all(), Resource::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		Resource::create($data);

		return Redirect::route('resources.index');
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$resource = Resource::findOrFail($id);

		return View::make('modelos.resources.show', compact('resource'));
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$resource = Resource::find($id);

		return View::make('modelos.resources.edit', compact('resource'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$resource = Resource::findOrFail($id);

		$validator = Validator::make($data = Input::all(), Resource::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$resource->update($data);

		return Redirect::route('resources.index');
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Resource::destroy($id);

		return Redirect::route('resources.index');
	}

}

<?php

class CommenttypesController extends BaseController {

	/**
	 * Display a listing of commenttypes
	 *
	 * @return Response
	 */
	public function index()
	{
		$commenttypes = Commenttype::all();

		return View::make('modelos.commenttypes.index', compact('commenttypes'));
	}

	/**
	 * Show the form for creating a new commenttype
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('modelos.commenttypes.create');
	}

	/**
	 * Store a newly created commenttype in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = Input::all(), Commenttype::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		Commenttype::create($data);

		return Redirect::route('commenttypes.index');
	}

	/**
	 * Display the specified commenttype.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$commenttype = Commenttype::findOrFail($id);

		return View::make('modelos.commenttypes.show', compact('commenttype'));
	}

	/**
	 * Show the form for editing the specified commenttype.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$commenttype = Commenttype::find($id);

		return View::make('modelos.commenttypes.edit', compact('commenttype'));
	}

	/**
	 * Update the specified commenttype in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$commenttype = Commenttype::findOrFail($id);

		$validator = Validator::make($data = Input::all(), Commenttype::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$commenttype->update($data);

		return Redirect::route('commenttypes.index');
	}

	/**
	 * Remove the specified commenttype from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Commenttype::destroy($id);

		return Redirect::route('commenttypes.index');
	}

}

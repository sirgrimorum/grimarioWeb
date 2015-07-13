<?php

class TasktypesController extends BaseController {

	/**
	 * Display a listing of tasktypes
	 *
	 * @return Response
	 */
	public function index()
	{
		$tasktypes = Tasktype::all();

		return View::make('modelos.tasktypes.index', compact('tasktypes'));
	}

	/**
	 * Show the form for creating a new tasktype
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('modelos.tasktypes.create');
	}

	/**
	 * Store a newly created tasktype in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$validator = Validator::make($data = Input::all(), Tasktype::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		Tasktype::create($data);

		return Redirect::route('tasktypes.index');
	}

	/**
	 * Display the specified tasktype.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$tasktype = Tasktype::findOrFail($id);

		return View::make('modelos.tasktypes.show', compact('tasktype'));
	}

	/**
	 * Show the form for editing the specified tasktype.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$tasktype = Tasktype::find($id);

		return View::make('modelos.tasktypes.edit', compact('tasktype'));
	}

	/**
	 * Update the specified tasktype in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		$tasktype = Tasktype::findOrFail($id);

		$validator = Validator::make($data = Input::all(), Tasktype::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$tasktype->update($data);

		return Redirect::route('tasktypes.index');
	}

	/**
	 * Remove the specified tasktype from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Tasktype::destroy($id);

		return Redirect::route('tasktypes.index');
	}

}

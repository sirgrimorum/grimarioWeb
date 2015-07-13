<?php

class CommentsController extends \BaseController {

    /**
     * Display a listing of comments
     *
     * @return Response
     */
    public function index() {
        $comments = Comment::all();

        return View::make('modelos.comments.index', compact('comments'));
    }

    /**
     * Show the form for creating a new comment
     *
     * @return Response
     */
    public function create() {
        if (Input::has('tk')) {
            $taskId = Input::get('tk');
            $task = Task::find($taskId);
            $user = Sentry::getUser();
            if (Input::has('wk')) {
                $workId = Input::get('wk');
                $work = Work::find($workId);
            } else {
                $work = $task->works()->where('user_id', '=', $user->id)->orderBy('start', 'desc')->first();
            }
            return View::make('modelos.comments.create', ['work' => $work, 'task' => $task, 'user' => $user]);
        } else {
            return View::make('modelos.comments.create');
        }
    }

    /**
     * Store a newly created comment in storage.
     *
     * @return Response
     */
    public function store() {
        $data = Input::except('image', 'redirect','commenttype');
        $data['type'] = Input::get('commenttype');
        $validator = Validator::make($data, Comment::$rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $file = Input::file('image');

        if ($file) {

            $destinationPath = public_path() . '/images/comments/';
            $filename = $file->getClientOriginalName();
            $filename = str_random(20) . "." . $file->getClientOriginalExtension();


            $upload_success = $file->move($destinationPath, $filename);

            if ($upload_success) {

                // resizing an uploaded file
                Image::make($destinationPath . $filename)->resize(50, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath . "thumb/" . $filename, 100);

                //return Response::json('success', 200);
            } else {
                return Response::json('error', 400);
            }
            $data = Input::except('_token', 'image', 'redirect','commenttype');
            $data['type'] = Input::get('commenttype');
            $data['image'] = $filename;

            Comment::create($data);
        } else {
            $data = Input::except('_token', 'image', 'redirect','commenttype');
            $data['type'] = Input::get('commenttype');
            Comment::create($data);
        }
        if (Input::has('redirect')) {
            return Redirect::to(Input::get('redirect'));
        } else {
            return Redirect::route(Lang::get("principal.menu.links.comentario") . '.index');
        }
    }

    /**
     * Display the specified comment.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        $comment = Comment::findOrFail($id);

        return View::make('modelos.comments.show', compact('comment'));
    }

    /**
     * Show the form for editing the specified comment.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        $comment = Comment::find($id);
        $task = $comment->task;
        return View::make('modelos.comments.edit', ['comment' => $comment, 'task' => $task]);
    }

    /**
     * Update the specified comment in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        $comment = Comment::findOrFail($id);
        $data = Input::except('image', 'image_nue', 'redirect','commenttype');
        $data['type'] = Input::get('commenttype');
        $validator = Validator::make($data, Comment::$rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $file = Input::file('image_nue');

        if ($file) {

            $destinationPath = public_path() . '/images/comments/';
            $filename = $file->getClientOriginalName();
            $filename = str_random(20) . "." . $file->getClientOriginalExtension();


            $upload_success = $file->move($destinationPath, $filename);

            if ($upload_success) {

                // resizing an uploaded file
                Image::make($destinationPath . $filename)->resize(50, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath . "thumb/" . $filename, 100);

                //return Response::json('success', 200);
            } else {
                return Response::json('error', 400);
            }
            $data = Input::except('_token', 'image', 'image_nue', 'redirect','commenttype');
            $data['type'] = Input::get('commenttype');
            $data['image'] = $filename;

            $comment->update($data);
        } else {
            $data = Input::except('_token', 'image_nue', 'redirect','commenttype');
            $data['type'] = Input::get('commenttype');
            $comment->update($data);
        }
        if (Input::has('redirect')) {
            return Redirect::to(Input::get('redirect'));
        } else {
            return Redirect::route(Lang::get("principal.menu.links.comentario") . '.index');
        }
    }

    /**
     * Remove the specified comment from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        Comment::destroy($id);

        return Redirect::route('comments.index');
    }

}

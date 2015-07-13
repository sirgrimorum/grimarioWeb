<?php

return array(
    'title' => Lang::get("comment.labels.cometarios"),
    'single' => Lang::get("comment.labels.comentario"),
    'model' => 'Comment',
    'columns' => array(
        'date' => array(
            'title' => Lang::get("comment.labels.date")
        ),
        'type' => array(
            'title' => Lang::get("comment.labels.type"),
            'type' => 'relationship',
            'name_field' => 'name',
        ),
        'public' => array(
            'title' => Lang::get("comment.labels.public")
        ),
        'user' => array(
            'title' => Lang::get("comment.labels.user_id"),
            'type' => 'relationship',
            'name_field' => 'email',
        ),
        'task' => array(
            'title' => Lang::get("comment.labels.task_id"),
            'type' => 'relationship',
            'name_field' => 'name',
        ),
    ),
    'edit_fields' => array(
        'date' => array(
            'title' => Lang::get("comment.labels.date"),
            'type' => 'datetime',
            'date_format' => 'dd-mm-yy',
            'time_format' => 'HH:mm',
            'description' => Lang::get("comment.descriptions.date")
        ),
        'user' => array(
            'title' => Lang::get("comment.labels.user_id"),
            'type' => 'relationship',
            'name_field' => 'name',
        ),
        'task' => array(
            'title' => Lang::get("comment.labels.task_id"),
            'type' => 'relationship',
            'name_field' => 'name',
        ),
        'type' => array(
            'title' => Lang::get("comment.labels.type"),
            'type' => 'relationship',
            'name_field' => 'name',
        ),
        'public' => array(
            'title' => Lang::get("comment.labels.public"),
            'type' => 'bool',
        ),
        'comment' => array(
            'title' => Lang::get("comment.labels.comment"),
            'type' => 'wysiwyg',
        ),
        'url' => array(
            'title' => Lang::get("comment.labels.url"),
            'type' => 'text',
            'limit' => 250,
        ),
        'image' => array(
            'title' => Lang::get("comment.labels.image"),
            'type' => 'image',
            'location' => public_path() . '/images/comments/',
            'naming' => 'random',
            'length' => 20,
            'size_limit' => 2,
            'sizes' => array(
                array(50, 50, 'fit', public_path() . '/images/comments/thumb/', 100),
            )
        ),
    ),
    'filters' => array(
        'type' => array(
            'title' => Lang::get("comment.labels.type"),
            'type' => 'enum',
            'options' => Lang::get("comment.selects.type"),
        ),
        'public' => array(
            'title' => Lang::get("comment.labels.public"),
            'type' => 'bool',
        ),
        'user' => array(
            'title' => Lang::get("comment.labels.user_id"),
            'type' => 'relationship',
            'name_field' => 'email'
        ),
        'task' => array(
            'title' => Lang::get("comment.labels.task_id"),
            'type' => 'relationship',
            'name_field' => 'name'
        ),
        'date' => array(
            'title' => Lang::get("comment.labels.date"),
            'type' => 'datetime',
        ),
    ),
    'rules' => array(
        'date' => 'required|date',
        'task_id' => 'required|integer|exist:tasks,id',
        'user_id' => 'required|integer|exist:users,id',
        'comment' => 'required',
        'url' => 'active_url',
    ),
    'messages' => array(
    /* 'name.required' => 'Es obligatorio establecer un nombre para la imagen',
      'image.required' => 'Es obligatorio seleccionar una imagen', */
    ),
    'sort' => array(
        'field' => 'date',
        'direction' => 'desc',
    ),
    'form_width' => 600,
);




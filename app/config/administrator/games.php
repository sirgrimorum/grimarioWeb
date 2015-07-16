<?php

return array(
    'title' => Lang::get("game.labels.juegos"),
    'single' => Lang::get("game.labels.juego"),
    'model' => 'Game',
    'columns' => array(
        'name' => array(
            'title' => Lang::get("game.labels.name")
        ),
        'type' => array(
            'title' => Lang::get("game.labels.type"),
            'output' => function($value) {
        return Lang::get("game.selects.type." . $value);
    },
        ),
        'enterprises' => array(
            'title' => Lang::get("game.labels.enterprises"),
            'relationship' => 'enterprises',
            //'type' => 'relationship',
            'select' => "GROUP_CONCAT((:table).nickname SEPARATOR ',')",
        ),
        'teams' => array(
            'title' => Lang::get("game.labels.teams"),
            'relationship' => 'teams',
            'select' => "GROUP_CONCAT((:table).name SEPARATOR ',')",
        ),
        'state' => array(
            'title' => Lang::get("game.labels.state"),
            'output' => function($value) {
        return Lang::get("game.selects.state." . $value);
    },
        ),
    ),
    'edit_fields' => array(
        'name' => array(
            'title' => Lang::get("game.labels.name"),
            'type' => 'text',
            'limit' => 50,
        ),
        'description' => array(
            'title' => Lang::get("game.labels.description"),
            'type' => 'textarea',
        ),
        'state' => array(
            'title' => Lang::get("game.labels.state"),
            'type' => 'enum',
            'options' => Lang::get("game.selects.state"),
            //'value' => '1',
        ),
        'type' => array(
            'title' => Lang::get("game.labels.type"),
            'type' => 'enum',
            'options' => Lang::get("game.selects.type"),
            //'value' => '1',
        ),
        'difficulty' => array(
            'title' => Lang::get("game.labels.difficulty"),
            'type' => 'number',
            'decimals' => 2,
            'value' => '50',
            'description' => Lang::get("game.descriptions.difficulty")
        ),
        'start' => array(
            'title' => Lang::get("game.labels.start"),
            'type' => 'datetime',
            'date_format' => 'dd-mm-yy',
            'time_format' => 'HH:mm',
            'description' => Lang::get("game.descriptions.start")
        ),
        'end' => array(
            'title' => Lang::get("game.labels.end"),
            'type' => 'datetime',
            'date_format' => 'dd-mm-yy', 
            'time_format' => 'HH:mm',
            'description' => Lang::get("game.descriptions.end")
        ),
        'enterprises' => array(
            'title' => Lang::get("game.labels.enterprises"),
            'type' => 'relationship',
            'name_field' => 'nickname',
        ),
        'teams' => array(
            'title' => Lang::get("game.labels.teams"),
            'type' => 'relationship',
            'name_field' => 'name',
        ),
        'prices' => array(
            'title' => Lang::get("game.labels.prices"),
            'type' => 'relationship',
            'name_field' => 'name',
        ),
        /*'tasks' => array(
            'title' => Lang::get("game.labels.tasks"),
            'type' => 'relationship',
            'name_field' => 'name',
            'editable' => false,
            'sort_field'=>'id'
        )*/
    ),
    'filters' => array(
        'name' => array(
            'title' => Lang::get("game.labels.name"),
        ),
        'start' => array(
            'title' => Lang::get("game.labels.start"),
            'type' => 'datetime',
        ),
        'end' => array(
            'title' => Lang::get("game.labels.end"),
            'type' => 'datetime',
        ),
        'type' => array(
            'title' => Lang::get("game.labels.type"),
            'type' => 'enum',
            'options' => Lang::get("game.selects.type"),
        ),
        'state' => array(
            'title' => Lang::get("game.labels.state"),
            'type' => 'enum',
            'options' => Lang::get("game.selects.state"),
        ),
        'enterprises' => array(
            'title' => Lang::get("game.labels.enterprises"),
            'relationship' => 'enterprises',
        ),
        'teams' => array(
            'title' => Lang::get("game.labels.teams"),
            'relationship' => 'teams',
        ),
    ),
    'rules' => array(
        'name' => 'required|min:8|unique:games,name',
        'state' => 'required',
        'type' => 'required',
        'difficulty' => 'required|numeric',
        'start' => 'required|date',
        'end' => 'required|date',
    ),
    'messages' => array(
    /* 'name.required' => 'Es obligatorio establecer un nombre para la imagen',
      'image.required' => 'Es obligatorio seleccionar una imagen', */
    ),
    'sort' => array(
        'field' => 'name',
        'direction' => 'desc',
    ),
    'form_width' => 600,
);




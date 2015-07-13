<?php

return array(
    'title' => Lang::get("team.labels.equipos"),
    'single' => Lang::get("team.labels.equipo"),
    'model' => 'Team',
    'columns' => array(
        'name' => array(
            'title' => Lang::get("team.labels.name")
        ),
        'type' => array(
            'title' => Lang::get("team.labels.type"),
            'output' => function($value) {
        return Lang::get("team.selects.type." . $value);
    },
        ),
        'state' => array(
            'title' => Lang::get("team.labels.state"),
            'output' => function($value) {
        return Lang::get("team.selects.state." . $value);
    },
        ),
        'logo' => array(
            'title' => Lang::get("team.labels.logo"),
            'output' => '<img src="' . asset('/images/teams/thumb/(:value)') . '" />',
        ),
    ),
    'edit_fields' => array(
        'name' => array(
            'title' => Lang::get("team.labels.name"),
            'type' => 'text',
            'limit' => 50,
        ),
        'logo' => array(
            'title' => Lang::get("team.labels.logo"),
            'type' => 'image',
            'location' => public_path() . '/images/teams/',
            'naming' => 'random',
            'length' => 20,
            'size_limit' => 1,
            'sizes' => array(
                array(50, 50, 'fit', public_path() . '/images/teams/thumb/', 100),
                array(100, 100, 'fit', public_path() . '/images/teams/mid/', 100),
            )
        ),
        'description' => array(
            'title' => Lang::get("team.labels.description"),
            'type' => 'textarea',
        ),
        'state' => array(
            'title' => Lang::get("team.labels.state"),
            'type' => 'enum',
            'options' => Lang::get("team.selects.state"),
            //'value' => '1',
        ),
        'type' => array(
            'title' => Lang::get("team.labels.type"),
            'type' => 'enum',
            'options' => Lang::get("team.selects.type"),
            //'value' => '1',
        ),
        'difficulty' => array(
            'title' => Lang::get("team.labels.difficulty"),
            'type' => 'number',
            'decimals' => 2,
            'value' => '50',
            'description' => Lang::get("team.descriptions.difficulty")
        ),
        'enterprises' => array(
            'title' =>  Lang::get("team.labels.enterprises"),
            'type' => 'relationship',
            'name_field' => 'nickname',
            //'editable' => false,
        ),
        'users' => array(
            'title' =>  Lang::get("team.labels.users"),
            'type' => 'relationship',
            'name_field' => 'name',
            //'editable' => false,
        )
    ),
    'filters' => array(
        'name' => array(
            'title' => Lang::get("team.labels.name"),
        ),
        'type' => array(
            'title' => Lang::get("team.labels.type"),
            'type' => 'enum',
            'options' => Lang::get("team.selects.type"),
        ),
        'state' => array(
            'title' => Lang::get("team.labels.state"),
            'type' => 'enum',
            'options' => Lang::get("team.selects.state"),
        ),
    ),
    'rules' => array(
        'name' => 'required|min:8|unique:teams,name',
        'state' => 'required',
        'difficulty' => 'required|numeric',
        'type' => 'required',
    ),
    'messages' => array(
        /*'name.required' => 'Es obligatorio establecer un nombre para la imagen',
        'image.required' => 'Es obligatorio seleccionar una imagen',*/
    ),
    'sort' => array(
        'field' => 'name',
        'direction' => 'desc',
    ),
    'form_width' => 600,
);




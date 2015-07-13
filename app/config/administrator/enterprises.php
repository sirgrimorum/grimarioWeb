<?php

return array(
    'title' => Lang::get("enterprise.labels.empresas"),
    'single' => Lang::get("enterprise.labels.empresa"),
    'model' => 'Enterprise',
    'columns' => array(
        'nickname' => array(
            'title' => Lang::get("enterprise.labels.nickname")
        ),
        'type' => array(
            'title' => Lang::get("enterprise.labels.type"),
            'output' => function($value) {
        return Lang::get("enterprise.selects.type." . $value);
    },
        ),
        'state' => array(
            'title' => Lang::get("enterprise.labels.state"),
            'output' => function($value) {
        return Lang::get("enterprise.selects.state." . $value);
    },
        ),
        'logo' => array(
            'title' => Lang::get("enterprise.labels.logo"),
            'output' => '<img src="' . asset('/images/enterprises/thumb/(:value)') . '" />',
        ),
        'fullname' => array(
            'title' => Lang::get("enterprise.labels.fullname")
        ),
    ),
    'edit_fields' => array(
        'nickname' => array(
            'title' => Lang::get("enterprise.labels.nickname"),
            'type' => 'text',
            'limit' => 30,
        ),
        'fullname' => array(
            'title' => Lang::get("enterprise.labels.fullname"),
            'type' => 'text',
            'limit' => 250,
        ),
        'logo' => array(
            'title' => Lang::get("enterprise.labels.logo"),
            'type' => 'image',
            'location' => public_path() . '/images/enterprises/',
            'naming' => 'random',
            'length' => 20,
            'size_limit' => 1,
            'sizes' => array(
                array(50, 50, 'fit', public_path() . '/images/enterprises/thumb/', 100),
                array(100, 100, 'fit', public_path() . '/images/enterprises/mid/', 100),
            )
        ),
        'description' => array(
            'title' => Lang::get("enterprise.labels.description"),
            'type' => 'textarea',
        ),
        'url' => array(
            'title' => Lang::get("enterprise.labels.url"),
            'type' => 'text',
            'limit' => 80,
        ),
        'linkedin' => array(
            'title' => Lang::get("enterprise.labels.linkedin"),
            'type' => 'text',
            'limit' => 150,
        ),
        'state' => array(
            'title' => Lang::get("enterprise.labels.state"),
            'type' => 'enum',
            'options' => Lang::get("enterprise.selects.state"),
            //'value' => '1',
        ),
        'type' => array(
            'title' => Lang::get("enterprise.labels.type"),
            'type' => 'enum',
            'options' => Lang::get("enterprise.selects.type"),
            //'value' => '1',
        ),
        'difficulty' => array(
            'title' => Lang::get("enterprise.labels.difficulty"),
            'type' => 'number',
            'decimals' => 2,
            'value' => '50',
            'description' => Lang::get("enterprise.descriptions.difficulty")
        ),
        'scale' => array(
            'title' => Lang::get("enterprise.labels.scale"),
            'type' => 'number',
            'decimals' => 0,
            'value' => '200',
            'description' => Lang::get("enterprise.descriptions.scale")
        ),
        'teams' => array(
            'title' =>  Lang::get("enterprise.labels.teams"),
            'type' => 'relationship',
            'name_field' => 'name',
            'editable' => false,
        ),
        'users' => array(
            'title' =>  Lang::get("enterprise.labels.users"),
            'type' => 'relationship',
            'name_field' => 'name',
            'editable' => false,
        )
    ),
    'filters' => array(
        'nickname' => array(
            'title' => Lang::get("enterprise.labels.nickname"),
        ),
        'type' => array(
            'title' => Lang::get("enterprise.labels.type"),
            'type' => 'enum',
            'options' => Lang::get("enterprise.selects.type"),
        ),
        'state' => array(
            'title' => Lang::get("enterprise.labels.state"),
            'type' => 'enum',
            'options' => Lang::get("enterprise.selects.state"),
        ),
    ),
    'rules' => array(
        'nickname' => 'required|alpha_num|min:6|unique:enterprises,nickname',
        'fullname' => 'required',
        'state' => 'required',
        'type' => 'required',
        'difficulty' => 'required|numeric',
        'scale' => 'required|integer',
        'url' => 'active_url',
        'linkedin' => 'active_url',
    ),
    'messages' => array(
        'name.required' => 'Es obligatorio establecer un nombre para la imagen',
        'image.required' => 'Es obligatorio seleccionar una imagen',
    ),
    'sort' => array(
        'field' => 'nickname',
        'direction' => 'desc',
    ),
    'form_width' => 600,
);




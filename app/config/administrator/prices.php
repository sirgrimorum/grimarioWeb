<?php

return array(
    'title' => Lang::get("price.labels.premios"),
    'single' => Lang::get("price.labels.premio"),
    'model' => 'Price',
    'columns' => array(
        'name' => array(
            'title' => Lang::get("price.labels.name")
        ),
        'type' => array(
            'title' => Lang::get("price.labels.type"),
            'output' => function($value) {
        return Lang::get("price.selects.type." . $value);
    },
        ),
        'badge' => array(
            'title' => Lang::get("price.labels.badge"),
            'output' => '<img src="' . asset('/images/prices/thumb/(:value)') . '" />',
        ),
    ),
    'edit_fields' => array(
        'name' => array(
            'title' => Lang::get("price.labels.name"),
            'type' => 'text',
            'limit' => 50,
        ),
        'badge' => array(
            'title' => Lang::get("price.labels.badge"),
            'type' => 'image',
            'location' => public_path() . '/images/prices/',
            'naming' => 'random',
            'length' => 20,
            'size_limit' => 1,
            'sizes' => array(
                array(50, 50, 'fit', public_path() . '/images/prices/thumb/', 100),
            )
        ),
        'description' => array(
            'title' => Lang::get("price.labels.description"),
            'type' => 'textarea',
        ),
        'type' => array(
            'title' => Lang::get("price.labels.type"),
            'type' => 'enum',
            'options' => Lang::get("price.selects.type"),
            //'value' => '1',
        ),
        'enterprises' => array(
            'title' =>  Lang::get("price.labels.enterprises"),
            'type' => 'relationship',
            'name_field' => 'nickname',
        ),
        'teams' => array(
            'title' =>  Lang::get("price.labels.teams"),
            'type' => 'relationship',
            'name_field' => 'name',
        ),
        'games' => array(
            'title' =>  Lang::get("price.labels.games"),
            'type' => 'relationship',
            'name_field' => 'name',
            'editable' => false,
        )
    ),
    'filters' => array(
        'name' => array(
            'title' => Lang::get("price.labels.name"),
        ),
        'type' => array(
            'title' => Lang::get("price.labels.type"),
            'type' => 'enum',
            'options' => Lang::get("price.selects.type"),
        ),
        'enterprices' => array(
            'title' => Lang::get("price.labels.enterprises"),
            'relationship' => 'enterprices',
        ),
        'teams' => array(
            'title' => Lang::get("price.labels.teams"),
            'relationship' => 'teams',
        ),
    ),
    'rules' => array(
        'name' => 'required|min:8|unique:prices,name',
        'badge' => 'required',
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




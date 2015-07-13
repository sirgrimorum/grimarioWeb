<?php

return array(
    'title' => Lang::get("proyect.labels.proyectos"),
    'single' => Lang::get("proyect.labels.proyecto"),
    'model' => 'Proyect',
    'columns' => array(
        'name' => array(
            'title' => Lang::get("proyect.labels.name")
        ),
        'code' => array(
            'title' => Lang::get("proyect.labels.code")
        ),
        'priority' => array(
            'title' => Lang::get("proyect.labels.priority"),
            'output' => function($value) {
        return Lang::get("proyect.selects.priority." . $value);
    },
        ),
        'type' => array(
            'title' => Lang::get("proyect.labels.type"),
            'output' => function($value) {
        return Lang::get("proyect.selects.type." . $value);
    },
        ),
        'enterprises' => array(
            'title' => Lang::get("proyect.labels.enterprises"),
            'relationship' => 'enterprises',
            //'type' => 'relationship',
            'select' => "GROUP_CONCAT((:table).nickname SEPARATOR ',')",
        ),
        'teams' => array(
            'title' => Lang::get("proyect.labels.teams"),
            'relationship' => 'teams',
            'select' => "GROUP_CONCAT((:table).name SEPARATOR ',')",
        ),
        'state' => array(
            'title' => Lang::get("proyect.labels.state"),
            'output' => function($value) {
        return Lang::get("proyect.selects.state." . $value);
    },
        ),
        'user' => array(
            'title' => Lang::get("proyect.labels.user_id"),
            'type' => 'relationship',
            'name_field' => 'name',
        ),
    ),
    'edit_fields' => array(
        'name' => array(
            'title' => Lang::get("proyect.labels.name"),
            'type' => 'text',
            'limit' => 50,
        ),
        'code' => array(
            'title' => Lang::get("proyect.labels.code"),
            'type' => 'text',
            'limit' => 20,
        ),
        'description' => array(
            'title' => Lang::get("proyect.labels.description"),
            'type' => 'textarea',
        ),
        'priority' => array(
            'title' => Lang::get("proyect.labels.priority"),
            'type' => 'enum',
            'options' => Lang::get("proyect.selects.priority"),
        //'value' => '1',
        ),
        'state' => array(
            'title' => Lang::get("proyect.labels.state"),
            'type' => 'enum',
            'options' => Lang::get("proyect.selects.state"),
        //'value' => '1',
        ),
        'type' => array(
            'title' => Lang::get("proyect.labels.type"),
            'type' => 'enum',
            'options' => Lang::get("proyect.selects.type"),
        //'value' => '1',
        ),
        'enterprises' => array(
            'title' => Lang::get("proyect.labels.enterprises"),
            'type' => 'relationship',
            'name_field' => 'nickname',
        ),
        'teams' => array(
            'title' => Lang::get("proyect.labels.teams"),
            'type' => 'relationship',
            'name_field' => 'name',
        ),
        'user' => array(
            'title' => Lang::get("proyect.labels.user_id"),
            'type' => 'relationship',
            'name_field' => 'name',
        ),
        /*'payments' => array(
            'title' => Lang::get("proyect.labels.payments"),
            'type' => 'relationship',
            'name_field' => 'name',
            'editable' => false,
            'sort_field' => 'id'
        ),
        'tasks' => array(
            'title' => Lang::get("proyect.labels.tasks"),
            'type' => 'relationship',
            'name_field' => 'name',
            'editable' => false,
            'sort_field' => 'id'
        )*/
    ),
    'filters' => array(
        'name' => array(
            'title' => Lang::get("proyect.labels.name"),
        ),
        'code' => array(
            'title' => Lang::get("proyect.labels.code"),
        ),
        'priority' => array(
            'title' => Lang::get("proyect.labels.priority"),
            'type' => 'enum',
            'options' => Lang::get("proyect.selects.priority"),
        ),
        'type' => array(
            'title' => Lang::get("proyect.labels.type"),
            'type' => 'enum',
            'options' => Lang::get("proyect.selects.type"),
        ),
        'state' => array(
            'title' => Lang::get("proyect.labels.state"),
            'type' => 'enum',
            'options' => Lang::get("proyect.selects.state"),
        ),
        'enterprises' => array(
            'title' => Lang::get("proyect.labels.enterprises"),
            'type' => 'relationship',
            'name_field' => 'nikname',
            'autocomplete' => true,
            'num_options' => 5,
        ),
        'teams' => array(
            'title' => Lang::get("proyect.labels.teams"),
            'type' => 'relationship',
            'name_field' => 'name',
            'autocomplete' => true,
            'num_options' => 5,
        ),
    ),
    'rules' => array(
        'name' => 'required|min:8|unique:proyects,name',
        'code' => 'alpha_num|min:6|unique:proyects,code',
        'state' => 'required',
        'type' => 'required',
        'priority' => 'required',
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




<?php

return array(
    'title' => Lang::get("machine.labels.maquinas"),
    'single' => Lang::get("machine.labels.maquina"),
    'model' => 'Machine',
    'columns' => array(
        'name' => array(
            'title' => Lang::get("machine.labels.name")
        ),
        'valueph' => array(
            'title' => Lang::get("machine.labels.valueph")
        ),
        'enterprise' => array(
            'title' => Lang::get("machine.labels.enterprise_id"),
            'relationship'=>'enterprise',
            'select' => '(:table).nickname',
        ),
    ),
    'edit_fields' => array(
        'name' => array(
            'title' => Lang::get("machine.labels.name"),
            'type' => 'text',
            'limit' => 150,
        ),
        'description' => array(
            'title' => Lang::get("machine.labels.description"),
            'type' => 'wysiwyg',
        ),
        'valueph' => array(
            'title' => Lang::get("machine.labels.valueph"),
            'type' => 'number',
            'decimals' => 2,
            'value' => '0',
            'description' => Lang::get("machine.descriptions.valueph")
        ),
        'enterprise' => array(
            'title' => Lang::get("machine.labels.enterprise_id"),
            'type' => 'relationship',
            'name_field' => 'nickname',
        ),
    ),
    'filters' => array(
        'name' => array(
            'title' => Lang::get("machine.labels.name"),
        ),
        'valueph' => array(
            'title' => Lang::get("machine.labels.valueph"),
            'type' => 'number',
        ),
    ),
    'rules' => array(
        'name' => 'required|min:6',
        'description' => 'required',
        'valueph' => 'required',
        'enterprise_id' => 'required|integer|exists:enterprises,id',
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




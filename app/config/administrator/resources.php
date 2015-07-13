<?php

return array(
    'title' => Lang::get("resource.labels.recursos"),
    'single' => Lang::get("resource.labels.recurso"),
    'model' => 'Resource',
    'columns' => array(
        'name' => array(
            'title' => Lang::get("resource.labels.name")
        ),
        'value' => array(
            'title' => Lang::get("resource.labels.value")
        ),
        'measure' => array(
            'title' => Lang::get("resource.labels.measure")
        ),
        'enterprise' => array(
            'title' => Lang::get("resource.labels.enterprise_id"),
            'relationship'=>'enterprise',
            'select' => '(:table).nickname',
        ),
    ),
    'edit_fields' => array(
        'name' => array(
            'title' => Lang::get("resource.labels.name"),
            'type' => 'text',
            'limit' => 150,
        ),
        'description' => array(
            'title' => Lang::get("resource.labels.description"),
            'type' => 'wysiwyg',
        ),
        'measure' => array(
            'title' => Lang::get("resource.labels.measure"),
            'type' => 'text',
            'limit' => 20,
            'description' => Lang::get("resource.descriptions.measure")
        ),
        'value' => array(
            'title' => Lang::get("resource.labels.value"),
            'type' => 'number',
            'decimals' => 2,
            'value' => '0',
            'description' => Lang::get("resource.descriptions.value")
        ),
        'enterprise' => array(
            'title' => Lang::get("resource.labels.enterprise_id"),
            'type' => 'relationship',
            'name_field' => 'nickname',
        ),
    ),
    'filters' => array(
        'name' => array(
            'title' => Lang::get("resource.labels.name"),
        ),
        'measure' => array(
            'title' => Lang::get("resource.labels.measure"),
        ),
        'value' => array(
            'title' => Lang::get("resource.labels.value"),
            'type' => 'number',
        ),
    ),
    'rules' => array(
        'name' => 'required|min:6',
        'description' => 'required',
        'value' => 'required',
        'measure' => 'required',
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




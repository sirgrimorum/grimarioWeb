<?php

return array(
    'title' => Lang::get("commenttype.labels.tipos"),
    'single' => Lang::get("commenttype.labels.tipo"),
    'model' => 'CommentType',
    'columns' => array(
        'name' => array(
            'title' => Lang::get("commenttype.labels.name")
        ),
        'tasktypes' => array(
            'title' => Lang::get("commenttype.labels.tasktypes"),
            'relationship'=>'tasktypes',
            'select' => "GROUP_CONCAT((:table).name SEPARATOR ',')",
        ),
    ),
    'edit_fields' => array(
        'name' => array(
            'title' => Lang::get("commenttype.labels.name"),
            'type' => 'text',
            'limit' => 150,
        ),
        'description' => array(
            'title' => Lang::get("commenttype.labels.description"),
            'type' => 'wysiwyg',
        ),
        'tasktypes' => array(
            'title' => Lang::get("commenttype.labels.tasktypes"),
            'type' => 'relationship',
            'name_field' => 'name',
        ),
    ),
    'filters' => array(
        'name' => array(
            'title' => Lang::get("commenttype.labels.name"),
        ),
    ),
    'rules' => array(
        'name' => 'required',
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




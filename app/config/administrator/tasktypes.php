<?php

return array(
    'title' => Lang::get("tasktype.labels.tipos"),
    'single' => Lang::get("tasktype.labels.tipo"),
    'model' => 'Tasktype',
    'columns' => array(
        'name' => array(
            'title' => Lang::get("tasktype.labels.name")
        ),
        'enterprise' => array(
            'title' => Lang::get("tasktype.labels.enterprise_id"),
            'relationship'=>'enterprise',
            'select' => '(:table).nickname',
        ),
        /*'commenttypes' => array(
            'title' => Lang::get("tasktype.labels.commenttypes"),
            'relationship'=>'commenttype',
            'select' => 'GROUP_CONCAT((:table).name SEPARATOR ',')',
        ),*/
    ),
    'edit_fields' => array(
        'name' => array(
            'title' => Lang::get("tasktype.labels.name"),
            'type' => 'text',
            'limit' => 150,
        ),
        'description' => array(
            'title' => Lang::get("tasktype.labels.description"),
            'type' => 'wysiwyg',
        ),
        'enterprise' => array(
            'title' => Lang::get("tasktype.labels.enterprise_id"),
            'type' => 'relationship',
            'name_field' => 'nickname',
        ),
    ),
    'filters' => array(
        'name' => array(
            'title' => Lang::get("machine.labels.name"),
        ),
    ),
    'rules' => array(
        'name' => 'required',
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




<?php

return array(
    'title' => Lang::get("task.labels.tareas"),
    'single' => Lang::get("task.labels.tarea"),
    'model' => 'Task',
    'columns' => array(
        'name' => array(
            'title' => Lang::get("task.labels.name")
        ),
        'code' => array(
            'title' => Lang::get("task.labels.code")
        ),
        'state' => array(
            'title' => Lang::get("task.labels.state"),
            'output' => function($value) {
        return Lang::get("task.selects.state." . $value);
    },
        ),
        'proyect' => array(
            'title' => Lang::get("task.labels.proyect_id"),
            'relationship' => 'proyect',
            'select' => '(:table).name',
        ),
        'game' => array(
            'title' => Lang::get("task.labels.game_id"),
            'relationship' => 'game',
            'select' => '(:table).name',
        ),
        'type' => array(
            'title' => Lang::get("task.labels.type"),
            'relationship' => 'tasktype',
            'select' => '(:table).name',
        ),
    ),
    'edit_fields' => array(
        'name' => array(
            'title' => Lang::get("task.labels.name"),
            'type' => 'text',
            'limit' => 50,
        ),
        'code' => array(
            'title' => Lang::get("task.labels.code"),
            'type' => 'text',
            'limit' => 20,
        ),
        'proyect' => array(
            'title' => Lang::get("task.labels.proyect_id"),
            'type' => 'relationship',
            'name_field' => 'name',
        ),
        'payments' => array(
            'title' => Lang::get("task.labels.payments"),
            'type' => 'relationship',
            'name_field' => 'name',
            //'editable' => false,
            'sort_field' => 'id'
        ),
        'game' => array(
            'title' => Lang::get("task.labels.game_id"),
            'type' => 'relationship',
            'name_field' => 'name',
        ),
        'state' => array(
            'title' => Lang::get("task.labels.state"),
            'type' => 'enum',
            'options' => Lang::get("task.selects.state"),
            'value' => '0',
        ),
        'order' => array(
            'title' => Lang::get("task.labels.order"),
            'type' => 'number',
            'decimals' => 0,
            'value' => '0',
        ),
        'tasktype' => array(
            'title' => Lang::get("task.labels.type"),
            'type' => 'relationship',
            'name_field' => 'name',
        ),
        'description' => array(
            'title' => Lang::get("task.labels.description"),
            'type' => 'wysiwyg',
        ),
        'result' => array(
            'title' => Lang::get("task.labels.result"),
            'type' => 'wysiwyg',
            'description' => Lang::get("task.descriptions.result")
        ),
        'difficulty' => array(
            'title' => Lang::get("task.labels.difficulty"),
            'type' => 'number',
            'decimals' => 2,
            'value' => '50',
            'description' => Lang::get("task.descriptions.difficulty")
        ),
        'plan' => array(
            'title' => Lang::get("task.labels.plan"),
            'type' => 'datetime',
            'date_format' => 'dd-mm-yy',
            'time_format' => 'HH:mm',
            'description' => Lang::get("task.descriptions.plan")
        ),
        'start' => array(
            'title' => Lang::get("task.labels.start"),
            'type' => 'datetime',
            'date_format' => 'dd-mm-yy',
            'time_format' => 'HH:mm',
            'description' => Lang::get("task.descriptions.start")
        ),
        'end' => array(
            'title' => Lang::get("task.labels.end"),
            'type' => 'datetime',
            'date_format' => 'dd-mm-yy',
            'time_format' => 'HH:mm',
            'description' => Lang::get("task.descriptions.end")
        ),
        'expenses' => array(
            'title' => Lang::get("task.labels.expenses"),
            'type' => 'number',
            'decimals' => 2,
            'description' => Lang::get("task.descriptions.expenses")
        ),
        'satisfaction' => array(
            'title' => Lang::get("task.labels.satisfaction"),
            'type' => 'enum',
            'options' => Lang::get("task.selects.satisfaction"),
            'value' => '0',
        ),
        'cuality' => array(
            'title' => Lang::get("task.labels.cuality"),
            'type' => 'enum',
            'options' => Lang::get("task.selects.cuality"),
            'value' => '0',
        ),
        'pcuantity' => array(
            'title' => Lang::get("task.labels.pcuantity"),
            'type' => 'number',
            'decimals' => 2,
            'description' => Lang::get("task.descriptions.pcuantity")
        ),
        'dcuantity' => array(
            'title' => Lang::get("task.labels.dcuantity"),
            'type' => 'number',
            'decimals' => 2,
            'description' => Lang::get("task.descriptions.dcuantity")
        ),
        'users' => array(
            'title' => Lang::get("task.labels.users"),
            'type' => 'relationship',
            'name_field' => 'name',
            //'editable' => false,
            'sort_field' => 'id'
        ),
    ),
    'filters' => array(
        'name' => array(
            'title' => Lang::get("task.labels.name"),
        ),
        'code' => array(
            'title' => Lang::get("task.labels.code"),
        ),
        'type' => array(
            'title' => Lang::get("task.labels.type"),
            'type' => 'enum',
            'options' => Lang::get("task.selects.type"),
        ),
        'state' => array(
            'title' => Lang::get("task.labels.state"),
            'type' => 'enum',
            'options' => Lang::get("task.selects.state"),
        ),
        'proyect' => array(
            'title' => Lang::get("task.labels.proyect_id"),
            'type' => 'relationship',
            'name_field' => 'name'
        ),
        'game' => array(
            'title' => Lang::get("task.labels.game_id"),
            'type' => 'relationship',
            'name_field' => 'name'
        ),
    ),
    'rules' => array(
        'name' => 'required|alpha_num|min:6',
        'result' => 'required',
        'state' => 'required',
        'type' => 'required',
        'difficulty' => 'required',
        'plan' => 'required|date',
        'expenses' => 'required|min:0',
        'proyect_id' => 'required|integer|exists:proyect,id',
        'game_id' => 'required|integer|exists:game,id',
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




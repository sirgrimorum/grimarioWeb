<?php

return array(
    'title' => Lang::get("payment.labels.pagos"),
    'single' => Lang::get("payment.labels.pago"),
    'model' => 'Payment',
    'columns' => array(
        'name' => array(
            'title' => Lang::get("payment.labels.name")
        ),
        'state' => array(
            'title' => Lang::get("payment.labels.state"),
            'output' => function($value) {
        return Lang::get("payment.selects.state." . $value);
    },
        ),
        'proyect' => array(
            'title' => Lang::get("payment.labels.proyect_id"),
            'relationship'=>'proyect',
            'select' => '(:table).name',
        ),
        'percentage' => array(
            'title' => Lang::get("payment.labels.percentage"),
            'type' => 'number',
            'symbol' => '%',
        ),
        'value' => array(
            'title' => Lang::get("payment.labels.value"),
            'type' => 'number',
            'symbol' => '$',
            'decimals' => 2,
        ),
        'plandate' => array(
            'title' => Lang::get("payment.labels.plandate")
        ),
        'paymentdate' => array(
            'title' => Lang::get("payment.labels.paymentdate")
        ),
    ),
    'edit_fields' => array(
        'name' => array(
            'title' => Lang::get("payment.labels.name"),
            'type' => 'text',
            'limit' => 50,
        ),
        'proyect' => array(
            'title' => Lang::get("payment.labels.proyect_id"),
            'type' => 'relationship',
            'name_field' => 'name',
        ),
        'percentage' => array(
            'title' => Lang::get("payment.labels.percentage"),
            'type' => 'number',
            'symbol' => '%',
        ),
        'value' => array(
            'title' => Lang::get("payment.labels.value"),
            'type' => 'number',
            'symbol' => '$',
            'decimals' => 2,
        ),
        'conditions' => array(
            'title' => Lang::get("payment.labels.conditions"),
            'type' => 'textarea',
        ),
        'plandate' => array(
            'title' => Lang::get("payment.labels.plandate"),
            'type' => 'date',
            'date_format' => 'dd-mm-yy',
            'description' => Lang::get("payment.descriptions.plandate")
        ),
        'state' => array(
            'title' => Lang::get("payment.labels.state"),
            'type' => 'enum',
            'options' => Lang::get("payment.selects.state"),
            'value' => '0',
        ),
        'paymentdate' => array(
            'title' => Lang::get("payment.labels.paymentdate"),
            'type' => 'date',
            'date_format' => 'dd-mm-yy',
            'description' => Lang::get("payment.descriptions.paymentdate")
        ),
        'tasks' => array(
            'title' => Lang::get("payment.labels.tasks"),
            'type' => 'relationship',
            'name_field' => 'name',
            'editable' => false,
            'sort_field'=>'id'
        )
    ),
    'filters' => array(
        'name' => array(
            'title' => Lang::get("payment.labels.name"),
        ),
        'percentage' => array(
            'title' => Lang::get("payment.labels.percentage"),
            'type' => 'number',
        ),
        'value' => array(
            'title' => Lang::get("payment.labels.value"),
            'type' => 'number',
        ),
        'state' => array(
            'title' => Lang::get("payment.labels.state"),
            'type' => 'enum',
            'options' => Lang::get("payment.selects.state"),
        ),
        'proyect' => array(
            'title' => Lang::get("payment.labels.proyect_id"),
            'type' => 'relationship',
            'name_field' => 'name'
        ),
    ),
    'rules' => array(
        'name' => 'required|min:6',
        'conditions' => 'required',
        'plandate' => 'date',
        'paymentdate' => 'date',
        'proyect_id' => 'required|integer',
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




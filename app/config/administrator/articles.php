<?php

return array(
    'title' => Lang::get("article.labels.articulos"),
    'single' => Lang::get("article.labels.articulo"),
    'model' => 'Article',
    'columns' => array(
        'lang' => array(
            'title' => Lang::get("article.labels.lang")
        ),
        'scope' => array(
            'title' => Lang::get("article.labels.scope")
        ),
        'nickname' => array(
            'title' => Lang::get("article.labels.nickname")
        ),
    ),
    'edit_fields' => array(
        'lang' => array(
            'title' => Lang::get("article.labels.lang"),
            'type' => 'enum',
            'options' => Lang::get("article.selects.lang"),
        ),
        'scope' => array(
            'title' => Lang::get("article.labels.scope"),
            'type' => 'text',
            'description' => 'La sección en donde aparece este artículo',
            //'editable' => false,
        ),
        'nickname' => array(
            'title' => Lang::get("article.labels.nickname"),
            'type' => 'text',
            //'editable' => false,
        ),
        'activated' => array(
            'title' => Lang::get("article.labels.activated"),
            'type' => 'bool'
        ),
        'content' => array(
            'title' => Lang::get("article.labels.content"),
            'type' => 'wysiwyg',
        ),
        'author' => array(
            'title' => Lang::get("article.labels.author"),
            'type' => 'relationship',
            'name_field' => 'email'
        )
    ),
    'filters' => array(
        'lang' => array(
            'title' => Lang::get("article.labels.lang"),
        ),
        'scope' => array(
            'title' => Lang::get("article.labels.scope"),
        ),
        'nickname' => array(
            'title' => Lang::get("article.labels.nickname"),
        ),
    ),
    'rules' => array(
        'lang' => 'required',
        'scope' => 'required',
        'nickname' => 'required',
        'content' => 'required',
    ),
    'messages' => array(
        'lang.required' => Lang::get("article.mensajes.lang.required"),
        'scope.required' => Lang::get("article.mensajes.scope.required"),
        'nickname.required' => Lang::get("article.mensajes.nickname.required"),
        'content.required' => Lang::get("article.mensajes.content.required"),
    ),
    'sort' => array(
        'field' => 'nickname',
        'direction' => 'desc',
    ),
    'form_width' => 600,
);

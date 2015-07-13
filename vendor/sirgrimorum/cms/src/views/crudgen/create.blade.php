@if (Session::has('message'))
<div class="alert alert-info">{{ Session::pull('message') }}</div>
@endif
<?php $errores = false ?>
@if (count($errors->all())>0)
<?php $errores = true ?>
@if (isset($config['render']))
<div class="alert alert-danger">
    {{ HTML::ul($errors->all()) }}
</div>
@endif
@endif

<?php
$tabla = $config['tabla'];
$campos = $config['campos'];
$botones = $config['botones'];
if (isset($config['files'])) {
    $files = $config['files'];
} else {
    $files = false;
}
if (isset($config['relaciones'])) {
    $relaciones = $config['relaciones'];
}
$url = $config['url'];
if (!isset($config['class_form'])) {
    $config['class_form'] = 'form-horizontal';
}
if (!isset($config['class_label'])) {
    $config['class_label'] = 'col-xs-12 col-sm-4 col-md-2';
}
if (!isset($config['class_divinput'])) {
    $config['class_divinput'] = 'col-xs-12 col-sm-8 col-md-10';
}
if (!isset($config['class_input'])) {
    $config['class_input'] = '';
}
if (!isset($config['class_offset'])) {
    $config['class_offset'] = 'col-xs-offset-0 col-sm-offset-4 col-md-offset-2';
}

if (isset($config['render'])) {
    $selects = array('column_name as field', 'column_type as type', 'is_nullable as null', 'column_key as key', 'column_default as default', 'extra as extra');
    $table_describes = DB::table('information_schema.columns')
            ->where('table_name', '=', $tabla)
            ->get($selects);
    foreach ($table_describes as $k => $v) {
        if (($kt = array_search($v, $table_describes)) !== false and $k != $kt) {
            unset($table_describes[$kt]);
        }
    }
}

echo Form::open(array('url' => $url, 'class' => $config['class_form'], 'files' => $files));

if (isset($config['render'])) {
    foreach ($table_describes as $key => $columna) {
        echo '<div class="form-group">';
        if (array_key_exists($columna->field, $campos)) {
            ?>
            @include("cms::crud.templates." . array_get($campos, $columna->field . ".tipo"))
            <?php
        } elseif (stripos($columna->type, "text") !== false) {
            echo Form::label($columna->field, ucfirst($columna->field), array('class' => $config['class_label']));
            echo '<div class="' . $config['class_divinput'] . '">';
            echo Form::textarea($columna->field, Input::old($columna->field), array('class' => 'form-control ' . $config['class_input']));
            echo '</div>';
        } elseif (isset($relaciones[$columna->field])) {
            echo Form::label($columna->field, ucfirst($columna->field), array('class' => $config['class_label']));
            echo '<div class="' . $config['class_divinput'] . '">';
            echo Form::select($columna->field, $relaciones[$columna->field]["todos"], Input::old($columna->field), array('class' => 'form-control ' . $config['class_input']));
            echo '</div>';
        } elseif ($columna->field != "created_at" && $columna->field != "updated_at" && $columna->key != "PRI") {
            echo Form::label($columna->field, ucfirst($columna->field), array('class' => $config['class_label']));
            echo '<div class="' . $config['class_divinput'] . '">';
            echo Form::text($columna->field, Input::old($columna->field), array('class' => 'form-control ' . $config['class_input']));
            echo '</div>';
        }
        echo '</div>';
    }
} else {
    foreach ($campos as $columna => $datos) {
        if (View::exists("cms::crudgen.templates." . $datos['tipo'])) {
            ?>
            @include("cms::crudgen.templates." . $datos['tipo'])
            <?php
        } else {
            ?>
            @include("cms::crudgen.templates.text")
            <?php
        }
    }
}
if (count($botones) > 0) {
    if (is_array($botones)) {
        echo '<div class="form-group">';
        foreach ($botones as $boton) {
            echo '<div class="' . $config['class_offset'] . ' ' . $config['class_divinput'] . '">';
            if (strpos($boton, "<") === false) {
                echo Form::submit($boton, array('class' => 'btn btn-primary'));
            } else {
                echo $boton;
            }
            echo '</div>';
        }
        echo '</div>';
    } else {
        echo '<div class="form-group">';
        echo '<div class="' . $config['class_offset'] . ' ' . $config['class_divinput'] . '">';
        if (strpos($botones, "<") === false) {
            echo Form::submit($botones, array('class' => 'btn btn-primary'));
        } else {
            echo $botones;
        }
        echo '</div>';
        echo '</div>';
    }
} else {
    echo '<div class="form-group">';
    echo '<div class="' . $config['class_offset'] . ' ' . $config['class_divinput'] . '">';
    echo Form::submit(Lang::get('cms::crud.create.titulo'), array('class' => 'btn btn-primary'));
    echo '</div>';
    echo '</div>';
}

echo Form::close();

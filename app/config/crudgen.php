<?php

return array(
    'game' => [
        "modelo" => "Game",
        "tabla" => "games",
        "nombre" => "name",
        "id" => "id",
        "url" => action('GamesController@store'),
        "botones" => Lang::get("game.labels.create"),
        "campos" => [
            "name" => [
                "tipo" => "text",
                "label" => Lang::get("game.labels.name"),
                "placeholder" => Lang::get("game.placeholders.name"),
                "enlace" => URL::route(Lang::get("principal.menu.links.juego") . '.show', array("{ID}")),
            ],
            "description" => [
                "tipo" => "html",
                "label" => Lang::get("game.labels.description"),
                "description" => Lang::get("game.descriptions.description"),
            ],
            "type" => [
                "tipo" => "select",
                "label" => Lang::get("game.labels.type"),
                "opciones" => Lang::get("game.selects.type")
            ],
            "difficulty" => [
                "tipo" => "number",
                "label" => Lang::get("game.labels.difficulty"),
                "placeholder" => Lang::get("game.placeholders.difficulty"),
                "valor" => 100,
                "description" => Lang::get("game.descriptions.difficulty"),
            ],
            "state" => [
                "tipo" => "select",
                "label" => Lang::get("game.labels.state"),
                "opciones" => Lang::get("game.selects.state"),
                "valor" => "pla",
            ],
            "start" => [
                "tipo" => "datetime",
                "label" => Lang::get("game.labels.date"),
                "placeholder" => Lang::get("game.placeholders.date"),
            ],
            "end" => [
                "tipo" => "datetime",
                "label" => Lang::get("game.labels.end"),
                "placeholder" => Lang::get("game.placeholders.end"),
            ],
            "teams" => [
                "label" => Lang::get("game.labels.teams"),
                "tipo" => "relationships",
                "modelo" => "Team",
                "id" => "id",
                "campo" => "name",
                "todos" => "",
                "enlace" => URL::to(Lang::get("principal.menu.links.equipo"), array("{ID}")),
            ],
        ],
    ],
    'proyect' => [
        "modelo" => "Proyect",
        "tabla" => "proyects",
        "nombre" => "name",
        "id" => "id",
        "url" => action('ProyectsController@store'),
        "botones" => Lang::get("proyect.labels.create"),
        "files" => true,
        "campos" => [
            "name" => [
                "tipo" => "text",
                "label" => Lang::get("proyect.labels.name"),
                "enlace" => URL::route(Lang::get("principal.menu.links.proyecto") . '.show', array("{ID}")),
                "placeholder" => Lang::get("task.placeholders.name"),
            ],
            "code" => [
                "tipo" => "text",
                "label" => Lang::get("proyect.labels.code"),
                "placeholder" => Lang::get("task.placeholders.code"),
            ],
            "description" => [
                "tipo" => "html",
                "label" => Lang::get("proyect.labels.description"),
                "description" => Lang::get("proyect.descriptions.description"),
            ],
            "priority" => [
                "tipo" => "select",
                "label" => Lang::get("proyect.labels.priority"),
                "opciones" => Lang::get("proyect.selects.priority")
            ],
            "objectives" => [
                "tipo" => "html",
                "label" => Lang::get("proyect.labels.objectives"),
                "description" => Lang::get("proyect.descriptions.objectives"),
            ],
            "problem" => [
                "tipo" => "html",
                "label" => Lang::get("proyect.labels.problem"),
                "description" => Lang::get("proyect.descriptions.problem"),
            ],
            "resources" => [
                "tipo" => "html",
                "label" => Lang::get("proyect.labels.resources"),
                "description" => Lang::get("proyect.descriptions.resources"),
            ],
            "experience" => [
                "tipo" => "html",
                "label" => Lang::get("proyect.labels.experience"),
                "description" => Lang::get("proyect.descriptions.experience"),
            ],
            "satisfaction" => [
                "tipo" => "html",
                "label" => Lang::get("proyect.labels.satisfaction"),
                "description" => Lang::get("proyect.descriptions.satisfaction"),
            ],
            "pop" => [
                "tipo" => "file",
                "label" => Lang::get("proyect.labels.pop"),
                "pathImage" => 'proyects/thumb/',
                "enlace" => asset("images/proyects/{value}"),
                "placeholder" => Lang::get("proyect.placeholders.pop"),
            ],
            "type" => [
                "tipo" => "select",
                "label" => Lang::get("proyect.labels.type"),
                "opciones" => Lang::get("proyect.selects.type")
            ],
            "state" => [
                "tipo" => "select",
                "label" => Lang::get("proyect.labels.state"),
                "opciones" => Lang::get("proyect.selects.state"),
                "valor" => "cre",
            ],
            "advance" => [
                "tipo" => "function",
                "label" => Lang::get("proyect.labels.advance"),
                "placeholder" => Lang::get("proyect.placeholders.advance"),
                "post" => "%",
            ],
            "totalplan" => [
                "tipo" => "function",
                "label" => Lang::get("proyect.labels.totalplan"),
                "placeholder" => Lang::get("proyect.placeholders.totalplan"),
                "pre" => "$",
                "post" => "COP",
                "format" => [0,".","."],
            ],
            "totalcost" => [
                "tipo" => "function",
                "label" => Lang::get("proyect.labels.totalcost"),
                "placeholder" => Lang::get("proyect.placeholders.totalcost"),
                "pre" => "$",
                "post" => "COP",
                "format" => [0,".","."],
            ],
            "saves" => [
                "tipo" => "function",
                "label" => Lang::get("proyect.labels.saves"),
                "placeholder" => Lang::get("proyect.placeholders.saves"),
                "pre" => "$",
                "post" => "COP",
                "format" => [0,".","."],
            ],
            "value" => [
                "tipo" => "function",
                "label" => Lang::get("proyect.labels.value"),
                "placeholder" => Lang::get("proyect.placeholders.value"),
                "pre" => "$",
                "post" => "COP",
                "format" => [0,".","."],
            ],
            "profit" => [
                "tipo" => "function",
                "label" => Lang::get("proyect.labels.profit"),
                "placeholder" => Lang::get("proyect.placeholders.profit"),
                "pre" => "$",
                "post" => "COP",
                "format" => [0,".","."],
            ],
            "enterprises" => [
                "label" => Lang::get("proyect.labels.enterprises"),
                "tipo" => "relationships",
                "modelo" => "Enterprise",
                "id" => "id",
                "campo" => "nickname",
                "enlace" => URL::route(Lang::get("principal.menu.links.empresa") . '.show', array("{ID}")),
                "todos" => ""
            ],
            "teams" => [
                "label" => Lang::get("proyect.labels.teams"),
                "tipo" => "relationships",
                "modelo" => "Team",
                "id" => "id",
                "campo" => "name",
                "enlace" => URL::route(Lang::get("principal.menu.links.equipo") . '.show', array("{ID}")),
                "todos" => ""
            ],
            "user_id" => [
                "label" => Lang::get("proyect.labels.user_id"),
                "tipo" => "relationship",
                "modelo" => "User",
                "id" => "id",
                "campo" => "name",
                //"enlace" => URL::route(Lang::get("principal.menu.links.usuario") . '.show', array("{ID}")),
                "todos" => ""
            ],
        ],
    ],
    'restriction' => [
        "modelo" => "Restriction",
        "tabla" => "restrictions",
        "nombre" => "name",
        "id" => "id",
        "botones" => Lang::get("restriction.labels.create"),
        "url" => action('RestrictionsController@store'),
        "campos" => [
            "name" => [
                "tipo" => "text",
                "label" => Lang::get("restriction.labels.name"),
                "placeholder" => Lang::get("restriction.placeholders.name"),
                "enlace" => URL::route(Lang::get("principal.menu.links.restriccion") . '.show', array("{ID}")),
            ],
            "proyect_id" => [
                "label" => Lang::get("restriction.labels.proyect_id"),
                "tipo" => "relationship",
                "modelo" => "Proyect",
                "id" => "id",
                "campo" => "name",
                "enlace" => URL::route(Lang::get("principal.menu.links.proyecto") . '.show', array("{ID}")),
                "todos" => ""
            ],
            "type" => [
                "tipo" => "select",
                "label" => Lang::get("restriction.labels.type"),
                "opciones" => Lang::get("restriction.selects.type")
            ],
            "description" => [
                "tipo" => "html",
                "label" => Lang::get("restriction.labels.description"),
                "description" => Lang::get("restriction.descriptions.description"),
            ],
            "state" => [
                "tipo" => "select",
                "label" => Lang::get("restriction.labels.state"),
                "opciones" => Lang::get("restriction.selects.state"),
                "valor" => "ide",
            ],
            "date" => [
                "tipo" => "date",
                "label" => Lang::get("restriction.labels.date"),
                "placeholder" => Lang::get("restriction.placeholders.date"),
            ],
            "comments" => [
                "tipo" => "html",
                "label" => Lang::get("restriction.labels.comments"),
                "description" => Lang::get("restriction.descriptions.comments"),
            ],
            "user_id" => [
                "label" => Lang::get("risk.labels.user_id"),
                "tipo" => "relationship",
                "modelo" => "User",
                "id" => "id",
                "campo" => "name",
                //"enlace" => URL::route(Lang::get("principal.menu.links.usuario") . '.show', array("{ID}")),
                "todos" => ""
            ],
        ],
    ],
    'payment' => [
        "modelo" => "Payment",
        "tabla" => "payments",
        "nombre" => "name",
        "id" => "id",
        "botones" => Lang::get("payment.labels.create"),
        "url" => action('PaymentsController@store'),
        "campos" => [
            "name" => [
                "tipo" => "text",
                "label" => Lang::get("payment.labels.name"),
                "placeholder" => Lang::get("payment.placeholders.name"),
                "enlace" => URL::route(Lang::get("principal.menu.links.pago") . '.show', array("{ID}")),
            ],
            "proyect_id" => [
                "label" => Lang::get("payment.labels.proyect_id"),
                "tipo" => "relationship",
                "modelo" => "proyect",
                "id" => "id",
                "campo" => "name",
                "enlace" => URL::route(Lang::get("principal.menu.links.proyecto") . '.show', array("{ID}")),
                "todos" => ""
            ],
            "state" => [
                "tipo" => "select",
                "label" => Lang::get("payment.labels.state"),
                "opciones" => Lang::get("payment.selects.state"),
                "valor" => "cre",
            ],
            "percentage" => [
                "tipo" => "number",
                "label" => Lang::get("payment.labels.percentage"),
                "placeholder" => Lang::get("payment.placeholders.percentage"),
                "description" => Lang::get("payment.descriptions.percentage"),
                "post" => "%",
            ],
            "value" => [
                "tipo" => "number",
                "label" => Lang::get("payment.labels.value"),
                "placeholder" => Lang::get("payment.placeholders.value"),
                "pre" => "$",
                "post" => "COP",
                "format" => [0,".","."],
                "description" => Lang::get("payment.descriptions.value"),
            ],
            "conditions" => [
                "tipo" => "html",
                "label" => Lang::get("payment.labels.conditions"),
                "description" => Lang::get("payment.descriptions.conditions"),
            ],
            "plan" => [
                "tipo" => "number",
                "label" => Lang::get("payment.labels.plan"),
                "placeholder" => Lang::get("payment.placeholders.plan"),
                "pre" => "$",
                "post" => "COP",
                "format" => [0,".","."],
            ],
            "advance" => [
                "tipo" => "function",
                "label" => Lang::get("payment.labels.advance"),
                "placeholder" => Lang::get("payment.placeholders.advance"),
                "post" => "%",
            ],
            "totalcost" => [
                "tipo" => "function",
                "label" => Lang::get("payment.labels.totalcost"),
                "placeholder" => Lang::get("payment.placeholders.totalcost"),
                "pre" => "$",
                "post" => "COP",
                "format" => [0,".","."],
            ],
            "profit" => [
                "tipo" => "function",
                "label" => Lang::get("payment.labels.profit"),
                "placeholder" => Lang::get("payment.placeholders.profit"),
                "pre" => "$",
                "post" => "COP",
                "format" => [0,".","."],
            ],
            "plandate" => [
                "tipo" => "date",
                "label" => Lang::get("payment.labels.plandate"),
                "placeholder" => Lang::get("payment.placeholders.plandate"),
            ],
            "paymentdate" => [
                "tipo" => "date",
                "label" => Lang::get("payment.labels.paymentdate"),
                "placeholder" => Lang::get("payment.placeholders.paymentdate"),
            ],
        ],
    ],
    'indicator' => [
        "modelo" => "Indicator",
        "tabla" => "indicators",
        "nombre" => "name",
        "id" => "id",
        "botones" => Lang::get("indicator.labels.create"),
        "url" => action('IndicatorsController@store'),
        "campos" => [
            "name" => [
                "tipo" => "text",
                "label" => Lang::get("indicator.labels.name"),
                "placeholder" => Lang::get("indicator.placeholders.name"),
                "enlace" => URL::route(Lang::get("principal.menu.links.indicador") . '.show', array("{ID}")),
            ],
            "payment_id" => [
                "label" => Lang::get("indicator.labels.payment_id"),
                "tipo" => "relationship",
                "modelo" => "Payment",
                "id" => "id",
                "campo" => "name",
                "enlace" => URL::route(Lang::get("principal.menu.links.pago") . '.show', array("{ID}")),
                "todos" => ""
            ],
            "type" => [
                "tipo" => "select",
                "label" => Lang::get("indicator.labels.type"),
                "opciones" => Lang::get("indicator.selects.type")
            ],
            "description" => [
                "tipo" => "html",
                "label" => Lang::get("indicator.labels.description"),
                "description" => Lang::get("indicator.descriptions.description"),
            ],
            "state" => [
                "tipo" => "select",
                "label" => Lang::get("indicator.labels.state"),
                "opciones" => Lang::get("indicator.selects.state"),
                "valor" => "cer",
            ],
            "priority" => [
                "tipo" => "select",
                "label" => Lang::get("indicator.labels.priority"),
                "opciones" => Lang::get("indicator.selects.priority")
            ],
            "date" => [
                "tipo" => "date",
                "label" => Lang::get("indicator.labels.date"),
                "placeholder" => Lang::get("indicator.placeholders.date"),
            ],
            "fuente" => [
                "tipo" => "text",
                "label" => Lang::get("indicator.labels.fuente"),
                "placeholder" => Lang::get("indicator.placeholders.fuente"),
                "description" => Lang::get("indicator.descriptions.fuente"),
            ],
            "musthave" => [
                "tipo" => "html",
                "label" => Lang::get("indicator.labels.musthave"),
                "description" => Lang::get("indicator.descriptions.musthave"),
            ],
            "nicetohave" => [
                "tipo" => "html",
                "label" => Lang::get("indicator.labels.nicetohave"),
                "description" => Lang::get("indicator.descriptions.nicetohave"),
            ],
            "ideal" => [
                "tipo" => "html",
                "label" => Lang::get("indicator.labels.ideal"),
                "description" => Lang::get("indicator.descriptions.ideal"),
            ],
            "user_id" => [
                "label" => Lang::get("indicator.labels.user_id"),
                "tipo" => "relationship",
                "modelo" => "User",
                "id" => "id",
                "campo" => "name",
                //"enlace" => URL::route(Lang::get("principal.menu.links.usuario") . '.show', array("{ID}")),
                "todos" => ""
            ],
        ],
    ],
    'risk' => [
        "modelo" => "Risk",
        "tabla" => "risks",
        "nombre" => "name",
        "id" => "id",
        "botones" => Lang::get("risk.labels.create"),
        "url" => action('RisksController@store'),
        "campos" => [
            "name" => [
                "tipo" => "text",
                "label" => Lang::get("risk.labels.name"),
                "placeholder" => Lang::get("risk.placeholders.name"),
                "enlace" => URL::route(Lang::get("principal.menu.links.riesgo") . '.show', array("{ID}")),
            ],
            "payment_id" => [
                "label" => Lang::get("risk.labels.payment_id"),
                "tipo" => "relationship",
                "modelo" => "Payment",
                "id" => "id",
                "campo" => "name",
                "enlace" => URL::route(Lang::get("principal.menu.links.pago") . '.show', array("{ID}")),
                "todos" => ""
            ],
            "type" => [
                "tipo" => "select",
                "label" => Lang::get("risk.labels.type"),
                "opciones" => Lang::get("risk.selects.type")
            ],
            "description" => [
                "tipo" => "html",
                "label" => Lang::get("risk.labels.description"),
                "description" => Lang::get("risk.descriptions.description"),
            ],
            "state" => [
                "tipo" => "select",
                "label" => Lang::get("risk.labels.state"),
                "opciones" => Lang::get("risk.selects.state"),
                "valor" => "pen",
            ],
            "probability" => [
                "tipo" => "number",
                "label" => Lang::get("risk.labels.probability"),
                "placeholder" => Lang::get("risk.placeholders.probability"),
                "valor" => 50,
                "description" => Lang::get("risk.descriptions.probability"),
                "post" => "%",
            ],
            "impact" => [
                "tipo" => "select",
                "label" => Lang::get("risk.labels.impact"),
                "opciones" => Lang::get("risk.selects.impact")
            ],
            "importance" => [
                "tipo" => "select",
                "label" => Lang::get("risk.labels.importance"),
                "opciones" => Lang::get("risk.selects.importance")
            ],
            "detect" => [
                "tipo" => "checkbox",
                "label" => Lang::get("risk.labels.detect"),
                "description" => Lang::get("risk.descriptions.detect"),
                "valor" => false,
                "value" => true
            ],
            "date" => [
                "tipo" => "date",
                "label" => Lang::get("risk.labels.date"),
                "placeholder" => Lang::get("risk.placeholders.date"),
            ],
            "trigger" => [
                "tipo" => "html",
                "label" => Lang::get("risk.labels.trigger"),
                "description" => Lang::get("risk.descriptions.trigger"),
            ],
            "response" => [
                "tipo" => "html",
                "label" => Lang::get("risk.labels.response"),
                "description" => Lang::get("risk.descriptions.response"),
            ],
            "plan" => [
                "tipo" => "html",
                "label" => Lang::get("risk.labels.plan"),
                "description" => Lang::get("risk.descriptions.plan"),
            ],
            "user_id" => [
                "label" => Lang::get("risk.labels.user_id"),
                "tipo" => "relationship",
                "modelo" => "User",
                "id" => "id",
                "campo" => "name",
                //"enlace" => URL::route(Lang::get("principal.menu.links.usuario") . '.show', array("{ID}")),
                "todos" => ""
            ],
        ],
    ],
    'task' => [
        "modelo" => "Task",
        "tabla" => "tasks",
        "nombre" => "name",
        "id" => "id",
        "url" => action('TasksController@store'),
        "campos" => [
            "name" => [
                "tipo" => "text",
                "label" => Lang::get("task.labels.name"),
                "placeholder" => Lang::get("task.placeholders.name"),
                "enlace" => URL::route(Lang::get("principal.menu.links.tarea") . '.show', array("{ID}")),
            ],
            "code" => [
                "tipo" => "text",
                "label" => Lang::get("task.labels.code"),
                "placeholder" => Lang::get("task.placeholders.code"),
            ],
            "proyect_id" => [
                "label" => Lang::get("task.labels.proyect_id"),
                "tipo" => "relationship",
                "modelo" => "Proyect",
                "id" => "id",
                "campo" => "name",
                "todos" => "",
                "enlace" => URL::route(Lang::get("principal.menu.links.proyecto") . '.show', array("{ID}")),
            ],
            "payments" => [
                "label" => Lang::get("task.labels.payments"),
                "tipo" => "relationships",
                "modelo" => "Payment",
                "id" => "id",
                "campo" => "name",
                "todos" => "",
                "enlace" => URL::route(Lang::get("principal.menu.links.pago") . '.show', array("{ID}")),
            ],
            "game_id" => [
                "label" => Lang::get("task.labels.game_id"),
                "tipo" => "relationship",
                "modelo" => "Game",
                "id" => "id",
                "campo" => "name",
                "todos" => "",
                "enlace" => URL::route(Lang::get("principal.menu.links.juego") . '.show', array("{ID}")),
            ],
            "percentage" => [
                "tipo" => "number",
                "label" => Lang::get("task.labels.percentage"),
                "placeholder" => Lang::get("task.placeholders.percentage"),
                "description" => Lang::get("task.descriptions.percentage"),
                "post" => "%",
            ],
            "dpercentage" => [
                "tipo" => "slider",
                "label" => Lang::get("task.labels.dpercentage"),
                "pre" => "",
                "post" => "%",
                "min" => 0,
                "max" => 100,
                "step" => 5,
                "description" => Lang::get("task.descriptions.dpercentage"),
                "post" => "%",
            ],
            "contribution" => [
                "tipo" => "function",
                "label" => Lang::get("task.labels.contribution"),
                "placeholder" => Lang::get("task.placeholders.contribution"),
                "post" => "%",
            ],
            "order" => [
                "tipo" => "number",
                "label" => Lang::get("task.labels.order"),
                "placeholder" => Lang::get("task.placeholders.order"),
                "description" => Lang::get("task.descriptions.order"),
            ],
            "tasktype" => [
                "label" => Lang::get("task.labels.type"),
                "tipo" => "relationship",
                "modelo" => "Tasktype",
                "id" => "id",
                "campo" => "name",
                "todos" => "",
                //"enlace" => URL::route(Lang::get("principal.menu.links.juego") . '.show', array("{ID}")),
            ],
            "description" => [
                "tipo" => "html",
                "label" => Lang::get("task.labels.description"),
                "description" => Lang::get("task.descriptions.description"),
            ],
            "result" => [
                "tipo" => "html",
                "label" => Lang::get("task.labels.result"),
                "description" => Lang::get("task.descriptions.result"),
            ],
            "difficulty" => [
                "tipo" => "number",
                "label" => Lang::get("task.labels.difficulty"),
                "placeholder" => Lang::get("task.placeholders.difficulty"),
                "valor" => 3,
                "description" => Lang::get("task.descriptions.difficulty"),
            ],
            "plan" => [
                "tipo" => "datetime",
                "label" => Lang::get("task.labels.plan"),
                "placeholder" => Lang::get("task.placeholders.plan"),
                "description" => Lang::get("task.descriptions.plan"),
            ],
            "expenses" => [
                "tipo" => "number",
                "label" => Lang::get("task.labels.expenses"),
                "placeholder" => Lang::get("task.placeholders.expenses"),
                "description" => Lang::get("task.descriptions.expenses"),
                "pre" => "$",
                "post" => "COP",
                "format" => [0,".","."],
            ],
            "pcuantity" => [
                "tipo" => "number",
                "label" => Lang::get("task.labels.pcuantity"),
                "placeholder" => Lang::get("task.placeholders.pcuantity"),
            ],
            "dcuantity" => [
                "tipo" => "number",
                "label" => Lang::get("task.labels.dcuantity"),
                "placeholder" => Lang::get("task.placeholders.dcuantity"),
            ],
            "state" => [
                "tipo" => "select",
                "label" => Lang::get("task.labels.state"),
                "opciones" => Lang::get("task.selects.state"),
                "valor" => "pla",
            ],
            "start" => [
                "tipo" => "datetime",
                "label" => Lang::get("task.labels.start"),
            ],
            "end" => [
                "tipo" => "datetime",
                "label" => Lang::get("task.labels.end"),
            ],
            "users" => [
                "label" => Lang::get("task.labels.users"),
                "tipo" => "relationships",
                "modelo" => "User",
                "id" => "id",
                "campo" => "name",
                "todos" => "",
                //"enlace" => URL::to(Lang::get("principal.menu.links.usuario"), array("{ID}")),
            ],
        ],
        "botones" => "Crear",
    ],
    'work' => [
        "modelo" => "Work",
        "tabla" => "works",
        "nombre" => "id",
        "id" => "id",
        "url" => action('WorksController@store'),
        "botones" => Lang::get("work.labels.create"),
        "campos" => [
            "name" => [
                "tipo" => "text",
                "label" => Lang::get("work.labels.name"),
                "placeholder" => Lang::get("work.placeholders.name"),
                "enlace" => URL::route(Lang::get("principal.menu.links.trabajo") . '.show', array("{ID}")),
            ],
            "coordinator" => [
                "label" => Lang::get("work.labels.coordinator"),
                "tipo" => "relationship",
                "modelo" => "User",
                "id" => "id",
                "campo" => "name",
                "todos" => "",
                "enlace" => URL::to(Lang::get("principal.menu.links.usuario"), array("{ID}")),
            ],
            "start" => [
                "tipo" => "datetime",
                "label" => Lang::get("work.labels.date"),
                "placeholder" => Lang::get("work.placeholders.date"),
            ],
            "end" => [
                "tipo" => "datetime",
                "label" => Lang::get("work.labels.end"),
                "placeholder" => Lang::get("work.placeholders.end"),
            ],
            "task_id" => [
                "label" => Lang::get("work.labels.task_id"),
                "tipo" => "relationship",
                "modelo" => "Task",
                "id" => "id",
                "campo" => "name",
                "todos" => "",
                "enlace" => URL::to(Lang::get("principal.menu.links.tarea"), array("{ID}")),
            ],
            "users" => [
                "label" => Lang::get("work.labels.users"),
                "tipo" => "relationships",
                "modelo" => "User",
                "id" => "id",
                "campo" => "name",
                "todos" => "",
                "enlace" => URL::to(Lang::get("principal.menu.links.usuario"), array("{ID}")),
            ],
        ],
    ],
    'comment' => [
        "modelo" => "Comment",
        "tabla" => "comments",
        "nombre" => "id",
        "id" => "id",
        "url" => action('CommentsController@store'),
        "botones" => Lang::get("comment.labels.create"),
        "files" => true,
        "campos" => [
            "date" => [
                "tipo" => "datetime",
                "label" => Lang::get("comment.labels.date"),
                "placeholder" => Lang::get("comment.placeholders.date"),
            ],
            "user_id" => [
                "label" => Lang::get("comment.labels.user_id"),
                "tipo" => "relationship",
                "modelo" => "User",
                "id" => "id",
                "campo" => "name",
                "todos" => "",
                "enlace" => URL::to(Lang::get("principal.menu.links.usuario"), array("{ID}")),
            ],
            "task_id" => [
                "label" => Lang::get("comment.labels.task_id"),
                "tipo" => "relationship",
                "modelo" => "Task",
                "id" => "id",
                "campo" => "name",
                "todos" => "",
                "enlace" => URL::to(Lang::get("principal.menu.links.tarea"), array("{ID}")),
            ],
            "commenttype" => [
                "label" => Lang::get("comment.labels.type"),
                "tipo" => "relationship",
                "modelo" => "Commenttype",
                "id" => "id",
                "campo" => "name",
                "todos" => "",
                //"enlace" => URL::route(Lang::get("principal.menu.links.juego") . '.show', array("{ID}")),
            ],
            "public" => [
                "tipo" => "checkbox",
                "label" => Lang::get("comment.labels.public"),
                "description" => Lang::get("comment.descriptions.public"),
                "valor" => false,
                "value" => true
            ],
            "comment" => [
                "tipo" => "html",
                "label" => Lang::get("comment.labels.comment"),
            ],
            "url" => [
                "tipo" => "url",
                "label" => Lang::get("comment.labels.url"),
                "placeholder" => Lang::get("comment.placeholders.url"),
            ],
            "image" => [
                "tipo" => "file",
                "label" => Lang::get("comment.labels.image"),
                "placeholder" => Lang::get("comment.placeholders.image"),
                "pathImage" => 'comments/thumb/',
                "enlace" => asset("images/comments/{value}")
            ],
        ],
    ],
    'cost' => [
        "modelo" => "Cost",
        "tabla" => "costs",
        "nombre" => "id",
        "id" => "id",
        "url" => action('CostsController@store'),
        "botones" => Lang::get("cost.labels.create"),
        "campos" => [
            "work_id" => [
                "label" => Lang::get("cost.labels.work_id"),
                "tipo" => "relationship",
                "modelo" => "Work",
                "id" => "id",
                "campo" => ["id", "start", "end"],
                "todos" => "",
                "enlace" => URL::to(Lang::get("principal.menu.links.trabajo"), array("{ID}")),
            ],
            "date" => [
                "tipo" => "datetime",
                "label" => Lang::get("cost.labels.date"),
                "placeholder" => Lang::get("cost.placeholders.date"),
            ],
            "type" => [
                "tipo" => "select",
                "label" => Lang::get("cost.labels.type"),
                "opciones" => Lang::get("cost.selects.type")
            ],
            "rubro" => [
                "tipo" => "select",
                "label" => Lang::get("cost.labels.rubro"),
                "opciones" => Lang::get("cost.selects.rubro")
            ],
            "description" => [
                "tipo" => "html",
                "label" => Lang::get("cost.labels.description"),
                "description" => Lang::get("cost.descriptions.description"),
            ],
            "code" => [
                "tipo" => "text",
                "label" => Lang::get("cost.labels.code"),
                "description" => Lang::get("cost.descriptions.code"),
                "placeholder" => Lang::get("cost.placeholders.code"),
            ],
            "value" => [
                "tipo" => "number",
                "label" => Lang::get("cost.labels.value"),
                "placeholder" => Lang::get("cost.placeholders.value"),
                "pre" => "$",
                "post" => "COP",
                "format" => [0,".","."],
            ],
            "user_id" => [
                "label" => Lang::get("cost.labels.user_id"),
                "tipo" => "relationship",
                "modelo" => "User",
                "id" => "id",
                "campo" => "name",
                "todos" => "",
                "enlace" => URL::to(Lang::get("principal.menu.links.usuario"), array("{ID}")),
            ],
        ],
    ],
    'team' => [
        "modelo" => "Team",
        "tabla" => "teams",
        "nombre" => "name",
        "id" => "id",
        "url" => action('TeamsController@store'),
        "botones" => Lang::get("team.labels.create"),
        "campos" => [
            "name" => [
                "tipo" => "text",
                "label" => Lang::get("team.labels.name"),
                "placeholder" => Lang::get("team.placeholders.name"),
                //"enlace" => URL::route(Lang::get("team.menu.links.equipos") . '.show', array("{ID}")),
            ],
            "description" => [
                "tipo" => "html",
                "label" => Lang::get("team.labels.description"),
                "description" => Lang::get("team.descriptions.description"),
            ],
            "type" => [
                "tipo" => "select",
                "label" => Lang::get("team.labels.type"),
                "opciones" => Lang::get("team.selects.type")
            ],
            "state" => [
                "tipo" => "select",
                "label" => Lang::get("team.labels.state"),
                "opciones" => Lang::get("team.selects.state")
            ],
            "difficulty" => [
                "tipo" => "number",
                "label" => Lang::get("team.labels.difficulty"),
                "placeholder" => Lang::get("team.placeholders.difficulty"),
            ],
            "logo" => [
                "tipo" => "file",
                "label" => Lang::get("team.labels.logo"),
                "placeholder" => Lang::get("team.placeholders.logo"),
                "pathImage" => 'teams/thumb/',
                "enlace" => asset("images/teams/{value}")
            ],
            "users" => [
                "label" => Lang::get("team.labels.users"),
                "tipo" => "relationships",
                "modelo" => "User",
                "id" => "id",
                "campo" => "name",
                "todos" => "",
                "enlace" => URL::to(Lang::get("principal.menu.links.usuario"), array("{ID}")),
            ],
        ],
    ],
);


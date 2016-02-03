<?php

class JsonsController extends \BaseController {

    /**
     * Display a listing of proyects
     *
     * @return Response
     */
    public function getGanttproyect($id) {
        $userSen = Sentry::getUser();
        $usuario = User::findOrFail($userSen->id);
        $proyect = Proyect::findOrFail($id);
        $resultado = [];
        $actual = time();
        foreach ($proyect->payments()->orderBy("plandate", "ASC")->get() as $payment) {
            $item = array(
                "name" => $payment->name,
                "desc" => "",
                "values" => array(array(
                        "from" => "/Date(" . ((strtotime($payment->plandate) - (24 * 60 * 60)) * 1000) . ")/",
                        "to" => "/Date(" . (strtotime($payment->plandate) * 1000) . ")/",
                        "label" => $payment->name,
                        "desc" => "<h3>" . $payment->name . "</h3><strong>" . Lang::get("payment.labels.plandate") . ":</strong> " . $payment->plandate . "<br/>" . $payment->conditions . "<strong>" . Lang::get("payment.labels.state") . ":</strong> " . Lang::get("payment.selects.state." . $payment->state),
                        "customClass" => "ganttGrey"
                    ))
            );
            array_push($resultado, $item);
            if ($payment->tasks()->count() > 0) {
                foreach ($payment->tasks()->orderBy("plan", "ASC")->get() as $task) {
                    if (strtotime($task->planstart) == 0) {
                        $task->planstart = date("Y-m-d H:i:s", $actual);
                        $task->save();
                    }
                    if ($task->state != "pla" or ($task->state == 'pla' && strtotime($task->plan) < time())) {
                        if ($task->state == "pla") {
                            $strFrom = "/Date(" . (time() * 1000) . ")/";
                            $strTo = "/Date(" . ((time() + (strtotime($task->plan) - strtotime($task->planstart))) * 1000) . ")/";
                            $strDesc = "<h3>" . $task->name . "</h3><strong>" . Lang::get("task.labels.state") . ":</strong> " . Lang::get("task.selects.state." . $task->state) . "<br/><strong>" . Lang::get("task.labels.dpercentage") . ":</strong> " . $task->dpercentage . "%<br/>";
                            $strDesc.="<h4>" . Lang::get("task.labels.retrasado") . (($task->timeleft() * -1) + ceil((strtotime($task->plan) - strtotime($task->planstart)) / 60 / 60)) . Lang::get("task.labels.dias") . "</h4>";
                            $strDesc.="<strong>" . Lang::get("task.labels.planstart") . ":</strong> " . $task->planstart . "<br/><strong>" . Lang::get("task.labels.plan") . ":</strong> " . $task->plan;
                            $strClass = "ganttRed";
                            $actual = strtotime($task->plan);
                        } elseif ($task->state == "des" || $task->state == "pau") {
                            $strFrom = "/Date(" . (strtotime($task->start) * 1000) . ")/";
                            if (strtotime($task->plan) > time()) {
                                $strTo = "/Date(" . (strtotime($task->plan) * 1000) . ")/";
                            } else {
                                $strTo = "/Date(" . (time() * 1000) . ")/";
                            }
                            $strDesc = "<h3>" . $task->name . "</h3><strong>" . Lang::get("task.labels.state") . ":</strong> " . Lang::get("task.selects.state." . $task->state) . "<br/><strong>" . Lang::get("task.labels.dpercentage") . ":</strong> " . $task->dpercentage . "%<br/>";
                            if ($task->timeleft() < 0) {
                                $strDesc.="<h4>" . Lang::get("task.labels.retrasado") . ($task->timeleft() * -1) . Lang::get("task.labels.dias") . "</h4>";
                            } else {
                                $strDesc.="<h4>" . Lang::get("task.labels.faltan") . $task->timeleft() . Lang::get("task.labels.dias") . "</h4>";
                            }
                            $strDesc.="<strong>" . Lang::get("task.labels.planstart") . ":</strong> " . $task->planstart . "<br/><strong>" . Lang::get("task.labels.start") . ":</strong> " . $task->start . "<br/><strong>" . Lang::get("task.labels.plan") . ":</strong> " . $task->plan;
                            $strClass = "ganttOrange";
                            $actual = strtotime($task->plan);
                        } elseif ($task->state == "ter") {
                            $strFrom = "/Date(" . (strtotime($task->start) * 1000) . ")/";
                            $strTo = "/Date(" . (strtotime($task->end) * 1000) . ")/";
                            $strDesc = "<h3>" . $task->name . "</h3><strong>" . Lang::get("task.labels.state") . ":</strong> " . Lang::get("task.selects.state." . $task->state) . "<br/>";
                            if ($task->timeleft() < 0) {
                                $strDesc.="<h4>" . Lang::get("task.labels.entregado") . ($task->timeleft() * -1) . Lang::get("task.labels.dias_despues") . "</h4>";
                                $strClass = "ganttRed";
                            } else {
                                $strDesc.="<h4>" . Lang::get("task.labels.entregado") . $task->timeleft() . Lang::get("task.labels.dias_antes") . "</h4>";
                                $strClass = "ganttGreen";
                            }
                            $strDesc.="<strong>" . Lang::get("task.labels.planstart") . ":</strong> " . $task->planstart . "<br/><strong>" . Lang::get("task.labels.start") . ":</strong> " . $task->start . "<br/><strong>" . Lang::get("task.labels.plan") . ":</strong> " . $task->plan . "<br/><strong>" . Lang::get("task.labels.end") . ":</strong> " . $task->end;
                            $actual = strtotime($task->end);
                        } else {
                            $strFrom = "/Date(" . (strtotime($task->start) * 1000) . ")/";
                            $strTo = "/Date(" . (strtotime($task->end) * 1000) . ")/";
                            $strDesc = "<h3>" . $task->name . "</h3><strong>" . Lang::get("task.labels.state") . ":</strong> " . Lang::get("task.selects.state." . $task->state) . "<br/>";
                            if ($task->timeleft() < 0) {
                                $strDesc.="<h4>" . Lang::get("task.labels.entregado") . ($task->timeleft() * -1) . Lang::get("task.labels.dias_despues") . "</h4>";
                                $strClass = "ganttRed";
                            } else {
                                $strDesc.="<h4>" . Lang::get("task.labels.entregado") . $task->timeleft() . Lang::get("task.labels.dias_antes") . "</h4>";
                                $strClass = "ganttGreen";
                            }
                            $strDesc.="<strong>" . Lang::get("task.labels.planstart") . ":</strong> " . $task->planstart . "<br/><strong>" . Lang::get("task.labels.start") . ":</strong> " . $task->start . "<br/><strong>" . Lang::get("task.labels.plan") . ":</strong> " . $task->plan . "<br/><strong>" . Lang::get("task.labels.end") . ":</strong> " . $task->end;
                            $strClass = "ganttGreen";
                            $actual = strtotime($task->end);
                        }
                        $strDesc .= "<br/><strong>" . Lang::get("task.labels.description") . ":</strong>" . $task->description . "<strong>" . Lang::get("task.labels.result") . ":</strong>" . $task->result;
                        $item = array(
                            "desc" => $task->name,
                            "values" => array(array(
                                    "from" => "/Date(" . (strtotime($task->planstart) * 1000) . ")/",
                                    "to" => "/Date(" . (strtotime($task->plan) * 1000) . ")/",
                                    "label" => $task->name,
                                    "desc" => $strDesc,
                                    "customClass" => "ganttBlue",
                                    "dataObj" => $task->id
                                ))
                        );
                        array_push($resultado, $item);
                        $item = array(
                            "desc" => "",
                            "values" => array(array(
                                    "from" => $strFrom,
                                    "to" => $strTo,
                                    "label" => $task->name,
                                    "desc" => $strDesc,
                                    "customClass" => $strClass,
                                    "dataObj" => $task->id
                                ))
                        );
                        array_push($resultado, $item);
                    } else {
                        $item = array(
                            "desc" => $task->name,
                            "values" => array(array(
                                    "from" => "/Date(" . (strtotime($task->planstart) * 1000) . ")/",
                                    "to" => "/Date(" . (strtotime($task->plan) * 1000) . ")/",
                                    "label" => $task->name,
                                    "desc" => "<h3>" . $task->name . "</h3><strong>" . Lang::get("task.labels.planstart") . ":</strong> " . $task->planstart . "<br/><strong>" . Lang::get("task.labels.plan") . ":</strong> " . $task->plan . "<br/><strong>" . Lang::get("task.labels.description") . ":</strong>" . $task->description . "<strong>" . Lang::get("task.labels.result") . ":</strong>" . $task->result . "<br/><strong>" . Lang::get("task.labels.state") . ":</strong> " . Lang::get("task.selects.state." . $task->state),
                                    "customClass" => "ganttBlue",
                                    "dataObj" => $task->id
                                ))
                        );
                        array_push($resultado, $item);
                        $actual = strtotime($task->plan);
                    }
                }
            }
            $actual = strtotime($payment->plandate);
        }
        //return"<pre>" . print_r($resultado, true) . "</pre>";
        return json_encode($resultado);
        //return Response::json($resultado);
    }

    public function getGanttuser($id) {
        $userSen = Sentry::getUser();
        $usuario = User::findOrFail($userSen->id);
        $user = User::findOrFail($id);
        $timestamp1 = mktime(0, 0, 0, date("n") - 1, 1, date("Y"));
        $whereQuery = " tasks.planstart >= '" . date("Y-m-d H:i:s", $timestamp1) . "' or tasks.plan >= '" . date("Y-m-d H:i:s", $timestamp1) . "' or tasks.start >= '" . date("Y-m-d H:i:s", $timestamp1) . "' or tasks.end >= '" . date("Y-m-d H:i:s", $timestamp1) . "' ";
        $resultado = [];
        $actual = time();
        $proName = "";
        foreach ($user->tasks()->whereRaw($whereQuery)->orderBy("proyect_id", "DESC")->orderBy("planstart", "ASC")->get() as $task) {
            if ($proName != $task->proyect->name) {
                $proName = $task->proyect->name;
                $item = array(
                    "name" => $proName,
                    "desc" => "",
                    "values" => array(array(
                            "from" => "/Date(" . (strtotime($task->planstart) * 1000) . ")/",
                            "to" => "/Date(" . (strtotime($task->plandate) * 1000) . ")/",
                            "label" => $proName,
                            "desc" => "<h3>" . $proName . "</h3>" . $task->proyect->description . "<strong>" . Lang::get("proyect.labels.state") . ":</strong> " . Lang::get("proyect.selects.state." . $task->proyect->state),
                            "customClass" => "ganttGrey"
                        ))
                );
                array_push($resultado, $item);
            }

            if (strtotime($task->planstart) == 0) {
                $task->planstart = date("Y-m-d H:i:s", $actual);
                $task->save();
            }
            if ($task->state != "pla" or ($task->state == 'pla' && strtotime($task->plan) < time())) {
                if ($task->state == "pla") {
                    $strFrom = "/Date(" . (time() * 1000) . ")/";
                    $strTo = "/Date(" . ((time() + (strtotime($task->plan) - strtotime($task->planstart))) * 1000) . ")/";
                    $strDesc = "<h3>" . $task->name . "</h3><strong>" . Lang::get("task.labels.state") . ":</strong> " . Lang::get("task.selects.state." . $task->state) . "<br/><strong>" . Lang::get("task.labels.dpercentage") . ":</strong> " . $task->dpercentage . "%<br/>";
                    $strDesc.="<h4>" . Lang::get("task.labels.retrasado") . (($task->timeleft() * -1) + ceil((strtotime($task->plan) - strtotime($task->planstart)) / 60 / 60)) . Lang::get("task.labels.dias") . "</h4>";
                    $strDesc.="<strong>" . Lang::get("task.labels.planstart") . ":</strong> " . $task->planstart . "<br/><strong>" . Lang::get("task.labels.plan") . ":</strong> " . $task->plan;
                    $strClass = "ganttRed";
                    $actual = strtotime($task->plan);
                } elseif ($task->state == "des" || $task->state == "pau") {
                    $strFrom = "/Date(" . (strtotime($task->start) * 1000) . ")/";
                    if (strtotime($task->plan) > time()) {
                        $strTo = "/Date(" . (strtotime($task->plan) * 1000) . ")/";
                    } else {
                        $strTo = "/Date(" . (time() * 1000) . ")/";
                    }
                    $strDesc = "<h3>" . $task->name . "</h3><strong>" . Lang::get("task.labels.state") . ":</strong> " . Lang::get("task.selects.state." . $task->state) . "<br/><strong>" . Lang::get("task.labels.dpercentage") . ":</strong> " . $task->dpercentage . "%<br/>";
                    if ($task->timeleft() < 0) {
                        $strDesc.="<h4>" . Lang::get("task.labels.retrasado") . ($task->timeleft() * -1) . Lang::get("task.labels.dias") . "</h4>";
                    } else {
                        $strDesc.="<h4>" . Lang::get("task.labels.faltan") . $task->timeleft() . Lang::get("task.labels.dias") . "</h4>";
                    }
                    $strDesc.="<strong>" . Lang::get("task.labels.planstart") . ":</strong> " . $task->planstart . "<br/><strong>" . Lang::get("task.labels.start") . ":</strong> " . $task->start . "<br/><strong>" . Lang::get("task.labels.plan") . ":</strong> " . $task->plan;
                    $strClass = "ganttOrange";
                    $actual = strtotime($task->plan);
                } elseif ($task->state == "ter") {
                    $strFrom = "/Date(" . (strtotime($task->start) * 1000) . ")/";
                    $strTo = "/Date(" . (strtotime($task->end) * 1000) . ")/";
                    $strDesc = "<h3>" . $task->name . "</h3><strong>" . Lang::get("task.labels.state") . ":</strong> " . Lang::get("task.selects.state." . $task->state) . "<br/>";
                    if ($task->timeleft() < 0) {
                        $strDesc.="<h4>" . Lang::get("task.labels.entregado") . ($task->timeleft() * -1) . Lang::get("task.labels.dias_despues") . "</h4>";
                        $strClass = "ganttRed";
                    } else {
                        $strDesc.="<h4>" . Lang::get("task.labels.entregado") . $task->timeleft() . Lang::get("task.labels.dias_antes") . "</h4>";
                        $strClass = "ganttGreen";
                    }
                    $strDesc.="<strong>" . Lang::get("task.labels.planstart") . ":</strong> " . $task->planstart . "<br/><strong>" . Lang::get("task.labels.start") . ":</strong> " . $task->start . "<br/><strong>" . Lang::get("task.labels.plan") . ":</strong> " . $task->plan . "<br/><strong>" . Lang::get("task.labels.end") . ":</strong> " . $task->end;
                    $actual = strtotime($task->end);
                } else {
                    $strFrom = "/Date(" . (strtotime($task->start) * 1000) . ")/";
                    $strTo = "/Date(" . (strtotime($task->end) * 1000) . ")/";
                    $strDesc = "<h3>" . $task->name . "</h3><strong>" . Lang::get("task.labels.state") . ":</strong> " . Lang::get("task.selects.state." . $task->state) . "<br/>";
                    if ($task->timeleft() < 0) {
                        $strDesc.="<h4>" . Lang::get("task.labels.entregado") . ($task->timeleft() * -1) . Lang::get("task.labels.dias_despues") . "</h4>";
                        $strClass = "ganttRed";
                    } else {
                        $strDesc.="<h4>" . Lang::get("task.labels.entregado") . $task->timeleft() . Lang::get("task.labels.dias_antes") . "</h4>";
                        $strClass = "ganttGreen";
                    }
                    $strDesc.="<strong>" . Lang::get("task.labels.planstart") . ":</strong> " . $task->planstart . "<br/><strong>" . Lang::get("task.labels.start") . ":</strong> " . $task->start . "<br/><strong>" . Lang::get("task.labels.plan") . ":</strong> " . $task->plan . "<br/><strong>" . Lang::get("task.labels.end") . ":</strong> " . $task->end;
                    $strClass = "ganttGreen";
                    $actual = strtotime($task->end);
                }
                $strDesc .= "<br/><strong>" . Lang::get("task.labels.description") . ":</strong>" . $task->description . "<strong>" . Lang::get("task.labels.result") . ":</strong>" . $task->result;
                $item = array(
                    "desc" => $task->name,
                    "values" => array(array(
                            "from" => "/Date(" . (strtotime($task->planstart) * 1000) . ")/",
                            "to" => "/Date(" . (strtotime($task->plan) * 1000) . ")/",
                            "label" => $task->name,
                            "desc" => $strDesc,
                            "customClass" => "ganttBlue",
                            "dataObj" => $task->id
                        ))
                );
                array_push($resultado, $item);
                $item = array(
                    "desc" => "",
                    "values" => array(array(
                            "from" => $strFrom,
                            "to" => $strTo,
                            "label" => $task->name,
                            "desc" => $strDesc,
                            "customClass" => $strClass,
                            "dataObj" => $task->id
                        ))
                );
                array_push($resultado, $item);
            } else {
                $item = array(
                    "desc" => $task->name,
                    "values" => array(array(
                            "from" => "/Date(" . (strtotime($task->planstart) * 1000) . ")/",
                            "to" => "/Date(" . (strtotime($task->plan) * 1000) . ")/",
                            "label" => $task->name,
                            "desc" => "<h3>" . $task->name . "</h3><strong>" . Lang::get("task.labels.planstart") . ":</strong> " . $task->planstart . "<br/><strong>" . Lang::get("task.labels.plan") . ":</strong> " . $task->plan . "<br/><strong>" . Lang::get("task.labels.description") . ":</strong>" . $task->description . "<strong>" . Lang::get("task.labels.result") . ":</strong>" . $task->result . "<br/><strong>" . Lang::get("task.labels.state") . ":</strong> " . Lang::get("task.selects.state." . $task->state),
                            "customClass" => "ganttBlue",
                            "dataObj" => $task->id
                        ))
                );
                array_push($resultado, $item);
                $actual = strtotime($task->plan);
            }
            //$actual = strtotime($task->plan);
        }
        //return"<pre>" . print_r($resultado, true) . "</pre>";
        return json_encode($resultado);
        //return Response::json($resultado);
    }

    public function getGanttteam($id) {
        $userSen = Sentry::getUser();
        $usuario = User::findOrFail($userSen->id);
        $team = Team::findOrFail($id);
        $timestamp1 = mktime(0, 0, 0, date("n") - 1, 1, date("Y"));
        $whereQuery = " tasks.planstart >= '" . date("Y-m-d H:i:s", $timestamp1) . "' or tasks.plan >= '" . date("Y-m-d H:i:s", $timestamp1) . "' or tasks.start >= '" . date("Y-m-d H:i:s", $timestamp1) . "' or tasks.end >= '" . date("Y-m-d H:i:s", $timestamp1) . "' ";
        $resultado = [];
        $actual = time();
        $proName = "";
        foreach ($team->teamtasks($whereQuery) as $task) {
            if ($proName != $task->proyect->name) {
                $proName = $task->proyect->name;
                $item = array(
                    "name" => $proName,
                    "desc" => "",
                    "values" => array(array(
                            "from" => "/Date(" . (strtotime($task->planstart) * 1000) . ")/",
                            "to" => "/Date(" . (strtotime($task->plandate) * 1000) . ")/",
                            "label" => $proName,
                            "desc" => "<h3>" . $proName . "</h3>" . $task->proyect->description . "<strong>" . Lang::get("proyect.labels.state") . ":</strong> " . Lang::get("proyect.selects.state." . $task->proyect->state),
                            "customClass" => "ganttGrey"
                        ))
                );
                array_push($resultado, $item);
            }

            if (strtotime($task->planstart) == 0) {
                $task->planstart = date("Y-m-d H:i:s", $actual);
                $task->save();
            }
            if ($task->state != "pla" or ($task->state == 'pla' && strtotime($task->plan) < time())) {
                if ($task->state == "pla") {
                    $strFrom = "/Date(" . (time() * 1000) . ")/";
                    $strTo = "/Date(" . ((time() + (strtotime($task->plan) - strtotime($task->planstart))) * 1000) . ")/";
                    $strDesc = "<h3>" . $task->name . "</h3><strong>" . Lang::get("task.labels.state") . ":</strong> " . Lang::get("task.selects.state." . $task->state) . "<br/><strong>" . Lang::get("task.labels.dpercentage") . ":</strong> " . $task->dpercentage . "%<br/>";
                    $strDesc.="<h4>" . Lang::get("task.labels.retrasado") . (($task->timeleft() * -1) + ceil((strtotime($task->plan) - strtotime($task->planstart)) / 60 / 60)) . Lang::get("task.labels.dias") . "</h4>";
                    $strDesc.="<strong>" . Lang::get("task.labels.planstart") . ":</strong> " . $task->planstart . "<br/><strong>" . Lang::get("task.labels.plan") . ":</strong> " . $task->plan;
                    $strClass = "ganttRed";
                    $actual = strtotime($task->plan);
                } elseif ($task->state == "des" || $task->state == "pau") {
                    $strFrom = "/Date(" . (strtotime($task->start) * 1000) . ")/";
                    if (strtotime($task->plan) > time()) {
                        $strTo = "/Date(" . (strtotime($task->plan) * 1000) . ")/";
                    } else {
                        $strTo = "/Date(" . (time() * 1000) . ")/";
                    }
                    $strDesc = "<h3>" . $task->name . "</h3><strong>" . Lang::get("task.labels.state") . ":</strong> " . Lang::get("task.selects.state." . $task->state) . "<br/><strong>" . Lang::get("task.labels.dpercentage") . ":</strong> " . $task->dpercentage . "%<br/>";
                    if ($task->timeleft() < 0) {
                        $strDesc.="<h4>" . Lang::get("task.labels.retrasado") . ($task->timeleft() * -1) . Lang::get("task.labels.dias") . "</h4>";
                    } else {
                        $strDesc.="<h4>" . Lang::get("task.labels.faltan") . $task->timeleft() . Lang::get("task.labels.dias") . "</h4>";
                    }
                    $strDesc.="<strong>" . Lang::get("task.labels.planstart") . ":</strong> " . $task->planstart . "<br/><strong>" . Lang::get("task.labels.start") . ":</strong> " . $task->start . "<br/><strong>" . Lang::get("task.labels.plan") . ":</strong> " . $task->plan;
                    $strClass = "ganttOrange";
                    $actual = strtotime($task->plan);
                } elseif ($task->state == "ter") {
                    $strFrom = "/Date(" . (strtotime($task->start) * 1000) . ")/";
                    $strTo = "/Date(" . (strtotime($task->end) * 1000) . ")/";
                    $strDesc = "<h3>" . $task->name . "</h3><strong>" . Lang::get("task.labels.state") . ":</strong> " . Lang::get("task.selects.state." . $task->state) . "<br/>";
                    if ($task->timeleft() < 0) {
                        $strDesc.="<h4>" . Lang::get("task.labels.entregado") . ($task->timeleft() * -1) . Lang::get("task.labels.dias_despues") . "</h4>";
                        $strClass = "ganttRed";
                    } else {
                        $strDesc.="<h4>" . Lang::get("task.labels.entregado") . $task->timeleft() . Lang::get("task.labels.dias_antes") . "</h4>";
                        $strClass = "ganttGreen";
                    }
                    $strDesc.="<strong>" . Lang::get("task.labels.planstart") . ":</strong> " . $task->planstart . "<br/><strong>" . Lang::get("task.labels.start") . ":</strong> " . $task->start . "<br/><strong>" . Lang::get("task.labels.plan") . ":</strong> " . $task->plan . "<br/><strong>" . Lang::get("task.labels.end") . ":</strong> " . $task->end;
                    $actual = strtotime($task->end);
                } else {
                    $strFrom = "/Date(" . (strtotime($task->start) * 1000) . ")/";
                    $strTo = "/Date(" . (strtotime($task->end) * 1000) . ")/";
                    $strDesc = "<h3>" . $task->name . "</h3><strong>" . Lang::get("task.labels.state") . ":</strong> " . Lang::get("task.selects.state." . $task->state) . "<br/>";
                    if ($task->timeleft() < 0) {
                        $strDesc.="<h4>" . Lang::get("task.labels.entregado") . ($task->timeleft() * -1) . Lang::get("task.labels.dias_despues") . "</h4>";
                        $strClass = "ganttRed";
                    } else {
                        $strDesc.="<h4>" . Lang::get("task.labels.entregado") . $task->timeleft() . Lang::get("task.labels.dias_antes") . "</h4>";
                        $strClass = "ganttGreen";
                    }
                    $strDesc.="<strong>" . Lang::get("task.labels.planstart") . ":</strong> " . $task->planstart . "<br/><strong>" . Lang::get("task.labels.start") . ":</strong> " . $task->start . "<br/><strong>" . Lang::get("task.labels.plan") . ":</strong> " . $task->plan . "<br/><strong>" . Lang::get("task.labels.end") . ":</strong> " . $task->end;
                    $strClass = "ganttGreen";
                    $actual = strtotime($task->end);
                }
                $strDesc .= "<br/><strong>" . Lang::get("task.labels.description") . ":</strong>" . $task->description . "<strong>" . Lang::get("task.labels.result") . ":</strong>" . $task->result;
                $item = array(
                    "desc" => $task->name,
                    "values" => array(array(
                            "from" => "/Date(" . (strtotime($task->planstart) * 1000) . ")/",
                            "to" => "/Date(" . (strtotime($task->plan) * 1000) . ")/",
                            "label" => $task->name,
                            "desc" => $strDesc,
                            "customClass" => "ganttBlue",
                            "dataObj" => $task->id
                        ))
                );
                array_push($resultado, $item);
                $item = array(
                    "desc" => "",
                    "values" => array(array(
                            "from" => $strFrom,
                            "to" => $strTo,
                            "label" => $task->name,
                            "desc" => $strDesc,
                            "customClass" => $strClass,
                            "dataObj" => $task->id
                        ))
                );
                array_push($resultado, $item);
            } else {
                $item = array(
                    "desc" => $task->name,
                    "values" => array(array(
                            "from" => "/Date(" . (strtotime($task->planstart) * 1000) . ")/",
                            "to" => "/Date(" . (strtotime($task->plan) * 1000) . ")/",
                            "label" => $task->name,
                            "desc" => "<h3>" . $task->name . "</h3><strong>" . Lang::get("task.labels.planstart") . ":</strong> " . $task->planstart . "<br/><strong>" . Lang::get("task.labels.plan") . ":</strong> " . $task->plan . "<br/><strong>" . Lang::get("task.labels.description") . ":</strong>" . $task->description . "<strong>" . Lang::get("task.labels.result") . ":</strong>" . $task->result . "<br/><strong>" . Lang::get("task.labels.state") . ":</strong> " . Lang::get("task.selects.state." . $task->state),
                            "customClass" => "ganttBlue",
                            "dataObj" => $payment->id
                        ))
                );
                array_push($resultado, $item);
                $actual = strtotime($task->plan);
            }
            //$actual = strtotime($task->plan);
        }
        //return"<pre>" . print_r($resultado, true) . "</pre>";
        return json_encode($resultado);
        //return Response::json($resultado);
    }

    public function getChartProAdvance($id) {
        $userSen = Sentry::getUser();
        $usuario = User::findOrFail($userSen->id);
        if ($userSen->hasAccess('proyects')) {
            $proyect = Proyect::findOrFail($id);
            $dtEntregasAv = Lava::DataTable();
            $dtEntregasAv->addStringColumn(Lang::get("payment.labels.pagos"));
            $arrPercentage = array(Lang::get("payment.labels.percentage"));
            $arrAdvance = array(Lang::get("payment.labels.advance"));
            foreach ($proyect->payments()->orderBy("plandate", "ASC")->get() as $payment) {
                $dtEntregasAv->addNumberColumn($payment->name);
                array_push($arrPercentage, $payment->percentage);
                array_push($arrAdvance, $payment->contribution());
            }
            $dtEntregasAv->addRow($arrPercentage);
            $dtEntregasAv->addRow($arrAdvance);
            return $dtEntregasAv->toJson();
        } else {
            return json_encode(Array());
        }
    }

    public function getChartProPresup($id) {
        $userSen = Sentry::getUser();
        $usuario = User::findOrFail($userSen->id);
        if ($userSen->hasAccess('proyects')) {
            $proyect = Proyect::findOrFail($id);
            $dtEntregasAv = Lava::DataTable();
            $dtEntregasAv->addStringColumn(Lang::get("payment.labels.pagos"));
            $arrValue = array(Lang::get("proyect.labels.value"));
            $arrTotalplan = array(Lang::get("proyect.labels.totalplan"));
            $arrSaves = array(Lang::get("proyect.labels.saves"));
            $arrTotalcost = array(Lang::get("proyect.labels.totalcost"));
            $arrProfit = array(Lang::get("proyect.labels.profit"));
            foreach ($proyect->payments()->orderBy("plandate", "ASC")->get() as $payment) {
                $dtEntregasAv->addNumberColumn($payment->name);
                array_push($arrValue, $payment->value);
                array_push($arrTotalplan, $payment->plan);
                array_push($arrSaves, $payment->saves());
                array_push($arrTotalcost, $payment->totalcost());
                array_push($arrProfit, $payment->profit());
            }
            if ($userSen->inGroup(Sentry::findGroupByName('Empresario')) || $userSen->inGroup(Sentry::findGroupByName('SuperAdmin'))) {
                $dtEntregasAv->addRow($arrValue);
            }
            $dtEntregasAv->addRow($arrTotalplan);
            $dtEntregasAv->addRow($arrSaves);
            $dtEntregasAv->addRow($arrTotalcost);
            if ($userSen->inGroup(Sentry::findGroupByName('Empresario')) || $userSen->inGroup(Sentry::findGroupByName('SuperAdmin'))) {
                $dtEntregasAv->addRow($arrProfit);
            }
            return $dtEntregasAv->toJson();
        } else {
            return json_encode(Array());
        }
    }

    public function getChartProHours($id) {
        $userSen = Sentry::getUser();
        $usuario = User::findOrFail($userSen->id);
        if ($userSen->hasAccess('proyects')) {
            $proyect = Proyect::findOrFail($id);
            $dtEntregasAv = Lava::DataTable();
            $dtEntregasAv->addStringColumn(Lang::get("payment.labels.pagos"));
            $arrTotalplanhours = array(Lang::get("proyect.labels.totalplanhours"));
            $arrTotalhours = array(Lang::get("proyect.labels.totalhours"));
            $arrSaveshours = array(Lang::get("proyect.labels.saveshours"));
            foreach ($proyect->payments()->orderBy("plandate", "ASC")->get() as $payment) {
                $dtEntregasAv->addNumberColumn($payment->name);
                array_push($arrTotalplanhours, $payment->planh);
                array_push($arrTotalhours, $payment->totalhours());
                array_push($arrSaveshours, $payment->saveshours());
            }
            $dtEntregasAv->addRow($arrTotalplanhours);
            $dtEntregasAv->addRow($arrTotalhours);
            $dtEntregasAv->addRow($arrSaveshours);
            return $dtEntregasAv->toJson();
        } else {
            return json_encode(Array());
        }
    }

    public function getChartProPayPresup($id) {
        $userSen = Sentry::getUser();
        $usuario = User::findOrFail($userSen->id);
        if ($userSen->hasAccess('proyects')) {
            $proyect = Proyect::findOrFail($id);
            $dtEntregasPer = Lava::DataTable();
            $dtEntregasPer->addStringColumn(Lang::get("payment.labels.value") . " " . Lang::get("payment.labels.pagos"));
            if ($userSen->inGroup(Sentry::findGroupByName('Empresario')) || $userSen->inGroup(Sentry::findGroupByName('SuperAdmin'))) {
                $dtEntregasPer->addNumberColumn(Lang::get("payment.labels.value"));
            }
            $dtEntregasPer->addNumberColumn(Lang::get("payment.labels.plan"))
                    ->addNumberColumn(Lang::get("payment.labels.totalcost"));
            if ($userSen->inGroup(Sentry::findGroupByName('Empresario')) || $userSen->inGroup(Sentry::findGroupByName('SuperAdmin'))) {
                $dtEntregasPer->addNumberColumn(Lang::get("payment.labels.profit"));
            }
            foreach ($proyect->payments()->orderBy("plandate", "ASC")->get() as $payment) {
                if ($userSen->inGroup(Sentry::findGroupByName('Empresario')) || $userSen->inGroup(Sentry::findGroupByName('SuperAdmin'))) {
                    $dtEntregasPer->addRow(array($payment->name, $payment->value, $payment->plan, $payment->totalcost(), $payment->profit()));
                } else {
                    $dtEntregasPer->addRow(array($payment->name, $payment->plan, $payment->totalcost()));
                }
            }
            return $dtEntregasPer->toJson();
        } else {
            return json_encode(Array());
        }
    }

    public function getChartProPayHours($id) {
        $userSen = Sentry::getUser();
        $usuario = User::findOrFail($userSen->id);
        if ($userSen->hasAccess('proyects')) {
            $proyect = Proyect::findOrFail($id);
            $dtEntregasPer = Lava::DataTable();
            $dtEntregasPer->addStringColumn(Lang::get("payment.labels.saveshours") . " " . Lang::get("payment.labels.pagos"))
                    ->addNumberColumn(Lang::get("payment.labels.planh"))
                    ->addNumberColumn(Lang::get("payment.labels.totalhours"))
                    ->addNumberColumn(Lang::get("payment.labels.saveshours"));
            foreach ($proyect->payments()->orderBy("plandate", "ASC")->get() as $payment) {
                $dtEntregasPer->addRow(array($payment->name, $payment->planh, $payment->totalhours(), $payment->saveshours()));
            }
            return $dtEntregasPer->toJson();
        } else {
            return json_encode(Array());
        }
    }

}

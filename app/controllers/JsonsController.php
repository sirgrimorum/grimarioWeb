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
                                    "dataObj" => $payment->id
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

}

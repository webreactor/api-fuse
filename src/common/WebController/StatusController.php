<?php

namespace Reactor\APIFuse\WebController;

class StatusController extends BaseController {

    function sent() {
        $list = $this->container['db']->sql('select * from task_sent order by sent desc limit 1000', array())->matr();
        echo "<table>\n";
        $fileds = array(
            'task_id',
            'scheduled_orig',
            'sent',
            'url',
            'retry_count',
            'resp_code',
            'resp_time',
        );
        echo "<tr>";
        foreach ($fileds as $key) {
            echo "<td>{$key}</td>\n";
        }
        echo "</tr>";

        foreach ($list as $line) {
            echo "<tr>";
            foreach ($fileds as $key) {
                echo "<td>{$line[$key]}</td>";
            }
            echo "</tr>\n";
        }
        echo '</table>';
    }

    function sent_task($request) {
        $task_id = (int)$request['route']->variables['task_id'];
        $list = $this->container['db']->sql('select * from task_sent where task_id=:task_id order by sent desc', array('task_id' => $task_id))->matr();
        echo "<pre>";
        print_r($list);
        echo "</pre>";
    }

}

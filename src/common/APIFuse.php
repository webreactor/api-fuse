<?php


namespace Reactor\APIFuse;


class APIFuse {

    function __construct($db, $http, $process_defaults, $curl_defaults) {
        $this->db = $db;
        $this->http = $http;
        $this->instance_id = uniqid("", true);
        $this->process_defaults = $process_defaults;
        $this->curl_defaults = $curl_defaults;
    }

    function assign() {
        return $this->db->sql('update task_list set worker = :worker, assigned = now() where scheduled <= now() and worker = "" limit 1', array('worker' => $this->instance_id))->count();
    }

    function getAssigned() {
        $list = $this->db->sql('select * from task_list where worker = :worker', array('worker' => $this->instance_id))->matr();
        foreach ($list as $k => $line) {
            $line['headers'] = json_decode($line['headers'], true);
            $line['curl_opts'] = json_decode($line['curl_opts'], true);
            $line['options'] = json_decode($line['options'], true);
            $list[$k] = $line;
        }
        return $list;
    }

    function processAssigned() {
        $list = $this->getAssigned();
        foreach ($list as $line) {
            $report = $this->processTask($line);
        }
        return count($list);
    }

    function cleanUpSent($ttl) {
        $this->db->sql("delete from task_sent where sent <= :ttl")->exec(array('ttl' => gmdate("Y-m-d H:i:s", time() - $ttl)));
    }

    function processTask($task) {
        $report = $this->processTaskExec($task);
        $this->addTaskSent($report);
        if (in_array($report['resp_info']['generic_code'], $task['options']['accept_codes'])) {
            $this->db->delete("task_list", array('task_id' => $task['task_id'])); // all good
        } else {
            if ($task['retry_count'] < $task['options']['max_retry']) {
                $this->db->sql('update task_list set worker="", retry_count=retry_count+1, scheduled=DATE_ADD(NOW(), INTERVAL :ttl SECOND) where task_id=:task_id', array('task_id' => $task['task_id'], 'ttl' => $task['options']['retry_ttl'])); // try again
            } else {
                $this->db->delete('task_list', array('task_id' => $task['task_id'])); // not good but max fail reached
            }
        }
        return $report;
    }

    function processTaskExec($task) {
        $report = $task;
        $report['sent'] = gmdate("Y-m-d H:i:s");
        $resp = $this->http->exec($task['method'], $task['url'], array(), $task['body'], $task['headers'], $task['curl_opts']);
        $report['resp_code'] = $resp['info']['http_code'];
        $report['resp_headers'] = $resp['response_header'];
        $report['resp_body'] = $resp['response_body'];
        $report['resp_time'] = $resp['info']['total_time'];
        $report['resp_info'] = $resp['info'];
        return $report;
    }

    function addTask($scheduled, $url, $method = "GET", $body = "", $headers = array(), $curl_opts = array(), $options = array()) {
        return $this->db->insert("task_list", array(
            "scheduled" => ''.$scheduled,
            "scheduled_orig" => ''.$scheduled,
            "method" => strtoupper($method),
            "url" => ''.$url,
            "body" => ''.$body,
            "headers" => json_encode($headers),
            "curl_opts" => json_encode($curl_opts + $this->curl_defaults),
            "options" => json_encode($options + $this->process_defaults),
        ));
    }

    function addTaskSent($report) {
        $report['resp_info'] = json_encode($report['resp_info']);
        $report['headers'] = json_encode($report['headers']);
        $report['curl_opts'] = json_encode($report['curl_opts']);
        $report['options'] = json_encode($report['options']);
        $this->db->insert("task_sent", $report);
    }

}

/*




/**/
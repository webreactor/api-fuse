<?php

namespace Reactor\APIFuse\WebController;

class CeleryController {

    function __construct($container) {
        $this->container = $container;
    }

    function createTask() {
        $celery_task = $this->getFromGlobals();
        if (empty($validation_result = $this->validate($celery_task)) {
            $id = $this->addTask($celery_task);
            $this->sendResponse(array(
                "state": "PENDING",
                "task-id": $id,
            ));
        } else {
            $this->sendResponse(array(
                "state": "error",
                "message": $validation_result
            ), 404);
        }
    }
    

    function sendResponse($data, $code = 200) {
        if ($code != 200) {
            http_response_code($code);
        }
        header("Content-Type: application/json");
        echo json_encode($data);
    }

    function getFromGlobals(&$validation) {
        $raw = (array)json_decode(stream_get_contents(STDIN), true);
        $data = array();
        if (empty($raw['eta'])) {
            $validation['eta'][] = 'cannot be empty';
        } else {
            $raw['eta'] = ''.$raw['eta'];
            if (strtotime($raw['eta']) === false) {
                $validation['eta'][] = 'must contain time';
            } else {
                $data['eta'] = $raw['eta'];
            }
        }
        if (empty($raw['args'])) {
            $validation['args'][] = 'cannot be empty';
        } else {
            $raw['args'] = (array)$raw['args'];
            if (empty($raw['args']['url'])) {
                $validation['args'] = 'url cannot be empty';
            } else {
                $data['url'] = ''.$raw['args']['url'];
            }
            if (empty($raw['args']['payload'])) {
                $validation['args'] = 'payload cannot be empty';
            } else {
                $data['payload'] = ''.$raw['args']['payload'];
            }
            if (empty($raw['args']['method'])) {
                $validation['args'] = 'method cannot be empty';
            } else {
                $data['method'] = ''.$raw['args']['method'];
            }
        }
        return $data;
    }

    function validate($task) {
        $errors = array();
        if (empty($task['url'])) {
            $errors['url'][] = 'cannot be empty';
        }
        return $data;
    }

    function addTask($celery_task) {
        $this->container['app']->addTask(
            $celery_task['eta'],
            $celery_task['url'],
            $celery_task['method'],
            json_encode($celery_task['body']),
            array(CURLOPT_HTTPHEADER => ['Content-Type: application/json'])
        );
    }

}




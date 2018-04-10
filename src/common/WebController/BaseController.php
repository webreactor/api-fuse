<?php

namespace Reactor\APIFuse\WebController;

class BaseController {

    function __construct($container) {
        $this->container = $container;
    }

    function sendResponse($data, $code = 200) {
        if ($code != 200) {
            http_response_code($code);
        }
        header("Content-Type: application/json");
        echo json_encode($data);
    }

    function getBody() {
        return (array)json_decode(stream_get_contents(STDIN), true);
    }

    function return404() {
        $this->sendResponse('Page not found', 404);
    }

}


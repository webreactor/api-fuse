<?php

namespace Reactor\APIFuse\WebController;

class HealthController extends BaseController {

    function health() {
        $this->sendResponse('all good');
    }

}


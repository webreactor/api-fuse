<?php

namespace Reactor\APIFuse\WebCore;

class SimpleRouterContext {

    public
        $cursor = 0,
        $node = null,
        $nodes = array(),
        $path = array(),
        $not_found = false,
        $not_found_node = null,
        $variables = array();

    function __construct($path) {
        $this->path = $path;
    }

}
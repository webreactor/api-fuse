<?php

include "../common/core.php";

// ($scheduled, $url, $method, $body, $headers, $curl_opts)
//$id = $container['app']->addTask(date("Y-m-d H:i:s", time()), "http://total-test.cloud.private.srvcam.com/", "GET");

//echo $id;


$router = new \Reactor\APIFuse\WebCore\SimpleRouter();

$tree = [ 
    'nodes' => [
        'health' => [
           'controller' => 'HealthController',
           'method' => 'health'
        ],
        'sent' => [
           'controller' => 'StatusController',
           'method' => 'sent',
           'nodes' => [
                '{variable}' => [
                    'name' => 'task_id',
                    'controller' => 'StatusController',
                    'method' => 'sent_task'
                ]
           ]
        ],
        'api' => [
            'nodes' => [
                'task' => [
                    'nodes' => [
                        'async-apply' => [
                            'nodes' => [
                                'tasks.make_request' => [
                                   'controller' => 'HealthController',
                                   'method' => 'health'
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ],
        '404' => [
           'controller' => 'BaseController',
           'method' => 'return404'
        ]
    ]
]
;


$route = $router->routePath($_SERVER["DOCUMENT_URI"], $tree);
$execute = $route->node + [ 'controller' => 'BaseController', 'method' => 'return404' ];
$controller_class = 'Reactor\\APIFuse\\WebController\\'.$execute['controller'];
$controller = new $controller_class($container);
call_user_func([$controller, $execute['method']], ['get' => $_GET, 'post' => $_POST, 'route' => $route]);


// if ($_SERVER["REQUEST_METHOD"] == "POST" && $_SERVER["DOCUMENT_URI"] == '/api/task/async-apply/tasks.make_request') {
//     $celery = new \Reactor\APIFuse\WebController($container);
//     $celery->createTask();
// }


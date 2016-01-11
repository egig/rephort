<?php

use Rych\Silex\Provider\PlatesServiceProvider;

require_once __DIR__.'/vendor/autoload.php';

$app = new Silex\Application(require_once __DIR__.'/config.php');

require_once $app['libphutil_path'].'/libphutil/src/__phutil_library_init__.php';

function call_conduit($method,  array $param) {

    global $app;

    $_client = new ConduitClient($app['phabricator_url']);
    $_client->setConduitToken($app['api_token']);

    $conduit = [
        'method' => $method,
        'param' => $param
    ];

    $hash = sha1(serialize($conduit));
    $cache_file = $app['cache_dir'].'/'.$hash;

    if(!file_exists($cache_file)) {
        $data = $_client->callMethodSynchronous($conduit['method'], $conduit['param']);
        file_put_contents($cache_file, serialize($data));
    } else {
        $data = unserialize(file_get_contents($cache_file));
    }

    return $data;
}

$_escaper = new Escaper;
$_template = new Template(__DIR__.'/_tpl', $_escaper);

/* HOME redirect to /{year}/{date} */
$app->get('/',function() use ($app){

    return 'coba /{username}';
});

/* MAIN */
$app->get('/{username}', function($username) use($app, $_template) {
    
    $data['base_path'] = $app['request']->getBaseUrl();

    $users = call_conduit('user.query', ['usernames' => [$username]]);

    $user = $users[0];

    $user['tasks'] = $tasks = call_conduit('maniphest.query', ['status' => 'status-open', 'ownerPHIDs' => [$user['phid']]]);

    $categories = [];
    $vals = [];

    foreach ($tasks as $task_phid => $task) {
        $start_working = $task['auxiliary']['std:maniphest:creasindo:start_working'];
        $target = $task['auxiliary']['std:maniphest:creasindo:target'];

        if($target && $start_working) {
            $categories[] = 'T'.$task['id'];
            $vals[] = [$start_working*1000, $target*1000];
        }
    }

    $data['categories'] = $categories;
    $data['data'] = $vals;

    return $_template->render('index', $data);
});

/* API to get json data */
$app->get('/api', function() use ($app) {

    $users = $_client->callMethodSynchronous('user.query', []);
    $colors = ['#80E680', '#4D4DFF', '#E066A3', '#DB4D4D', '#FF944D', '#A38566', '#FFFF66'];

    $projectColors = [];
    
    foreach ($users as &$user) {
        $open_tasks  = $_client->callMethodSynchronous('maniphest.query', ['status' => 'status-open', 'ownerPHIDs' => [$user['phid']]]);

        // filter target
        $targeted_tasks = [];
        foreach ($open_tasks as $phid  => $task) {
            if($task['auxiliary']['std:maniphest:creasindo:target'] != null) {

                // group by projects
                foreach ($task['projectPHIDs'] as $i => $projectPHID) {

                    if(!isset($targeted_tasks[$projectPHID])) {
                        $targeted_tasks[$projectPHID] = [];
                    }

                    $targeted_tasks[$projectPHID][] = $task;

                    if(!isset($projectColors[$projectPHID])) {
                        $projectColors[$projectPHID] = array_shift($colors);
                    }
                }
            }
        }

        $user['targeted_tasks'] = $targeted_tasks;
    }

    $response['users'] = $users;
    $response['projectColors'] = $projectColors;

    return $app->json($response);
});

$app->run(); 
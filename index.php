<?php

use Rych\Silex\Provider\PlatesServiceProvider;

require_once __DIR__.'/vendor/autoload.php';

$app = new Silex\Application(require_once __DIR__.'/config.php');

require_once $app['libphutil_path'].'/libphutil/src/__phutil_library_init__.php';

$_client = new ConduitClient($app['phabricator_url']);
$_client->setConduitToken($app['api_token']);


$_escaper = new Escaper;
$_template = new Template(__DIR__.'/_tpl', $_escaper);

/* HOME redirect to /{year}/{date} */
$app->get('/',function() use ($app){

    $url = $app['request']->getBaseUrl().'/'.date('Y').'/'.date('n');
    return $app->redirect($url);
});

/* MAIN */
$app->get('/{year}/{month}', function($year, $month) use($app, $_client, $_template) {
    
    $data['base_path'] = $app['request']->getBaseUrl();

    $data['year'] = $year;
    $data['month'] = $month;

    $data['users'] = $_client->callMethodSynchronous('user.query', []);
    $data['days'] = cal_days_in_month(CAL_GREGORIAN, $month, $year);
    return $_template->render('index', $data);
});

/* API to get json data */
$app->get('/api', function() use ($app, $_client) {

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
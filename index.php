<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use ShoperPL\ShoperDistanceAPI\Management\ShoperDistanceApi;
use ShoperPL\ShoperDistanceAPI\Router;
use ShoperPL\ShoperDistanceAPI\Request;
use ShoperPL\ShoperDistanceAPI\Response;
use ShoperPL\ShoperDistanceAPI\Model\Office;
use ShoperPL\ShoperDistanceAPI\Constants\HttpCodes;

Router::delete('/office([0-9]*', function (Request $request, Response $response) {
    $office = Office::delete($request->getJSON());
    $response->status(204)->toJSON($office);
});

Router::get('/office/all', function (Request $request, Response $response) {
    $offices = (new ShoperDistanceApi())->getAll();

    if (!empty($offices)) {
        $response->status(HttpCodes::HTTP_OK)->toJSON($offices);
    } else {
        $response->status(HttpCodes::HTTP_NOT_FOUND)->toJSON(['error' => "Nieznalezione"]);
    };
});

Router::get('/office/([0-9]*)', function (Request $request, Response $response) {
    $office = (new ShoperDistanceApi())->getById((int)$request->params[0]);

    if ($office) {
        $response->status(HttpCodes::HTTP_OK)->toJSON(($office));
    } else {
        $response->status(HttpCodes::HTTP_NOT_FOUND)->toJSON(['error' => "Nieznalezione"]);
    };
});

Router::get('/office-distance', function (Request $request, Response $response) {
    $office = Office::add($request->getJSON());
    $response->status(204)->toJSON($office);
});

Router::post('/office', function (Request $request, Response $response) {
    (new ShoperDistanceApi())->add($request);

    $response->status(HttpCodes::HTTP_OK)->toJSON(($response->toJSON()));
    // if ($office) {
    //     $respone->status(HttpCodes::HTTP_OK)->toJSON(($office));
    // } else {
    //     $respone->status(HttpCodes::HTTP_NOT_FOUND)->toJSON(['error' => "Nieznalezione"]);
    // };
});

Router::put('/office', function (Request $request, Response $response) {
    $office = Office::put($request->getJSON());
    $response->status(204)->toJSON($office);
});

(new shoperDistanceApi())->run();

// $LOG_PATH = Config::get('LOG_PATH', '');
// echo "[LOG_PATH]: $LOG_PATH";

// Logger::enableSystemLogs();
// $logger = Logger::getInstance();
// $logger->info('Hello World');

// Controller::run();

// Router::get('/', function () {
//     echo 'Hello World';
// });

// Router::get('/post/([0-9]*)', function (Request $req, Response $res) {
//     $res->toJSON([
//         'post' =>  ['id' => $req->params[0]],
//         'status' => 'ok'
//     ]);
// });
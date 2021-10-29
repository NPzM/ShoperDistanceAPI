<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use ShoperPL\ShoperDistanceAPI\Management\ShoperDistanceApi;
use ShoperPL\ShoperDistanceAPI\Router;
use ShoperPL\ShoperDistanceAPI\Request;
use ShoperPL\ShoperDistanceAPI\Response;
use ShoperPL\ShoperDistanceAPI\Model\Office;
use ShoperPL\ShoperDistanceAPI\Constants\HttpCodes;

Router::delete('/office', function (Request $request, Response $respone) {
    $office = Office::delete($request->getJSON());
    $respone->status(204)->toJSON($office);
});

Router::get('/office/all', function (Request $request, Response $respone) {
    $offices = (new ShoperDistanceApi())->getAll();

    if (!empty($offices)) {
        $respone->status(HttpCodes::HTTP_OK)->toJSON((new ShoperDistanceApi())->getAll());
    } else {
        $respone->status(HttpCodes::HTTP_NOT_FOUND)->toJSON(['error' => "Nieznalezione"]);
    };
});

Router::get('/office/([0-9]*)', function (Request $request, Response $respone) {
    $office = Office::findById($request->params[0]);
    if ($office) {
        $respone->toJSON($office);
    } else {
        $respone->status(404)->toJSON(['error' => "Not Found"]);
    }
});

Router::get('/office-distance', function (Request $request, Response $respone) {
    $office = Office::add($request->getJSON());
    $respone->status(204)->toJSON($office);
});

Router::post('/office', function (Request $request, Response $respone) {
    $office = Office::add($request->getJSON());
    $respone->status(204)->toJSON($office);
});

Router::put('/office', function (Request $request, Response $respone) {
    $office = Office::put($request->getJSON());
    $respone->status(204)->toJSON($office);
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